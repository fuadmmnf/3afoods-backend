<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\Product;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;


class OrderController extends Controller
{

    protected $firebaseService;
    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $orders = Order::all();
            return ResponseHelper::success($orders, 'Orders retrieved successfully', 200);
        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to retrieve orders', 500, $e->getMessage());
        }
    }

    public function sendDummyData(Request $request)
    {
        try {
            $data = [
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                // Add other data as needed
            ];

            // Send data to Firebase
            $firebaseKey = $this->firebaseService->sendEmail($data);

            return response()->json(['message' => 'Data sent to Firebase successfully', 'firebase_key' => $firebaseKey], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to send data to Firebase', 'message' => $e->getMessage()], 500);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderRequest $request)
    {
        try {
            // Get the authenticated user's ID
            $userId = Auth::id();
            // Validate and create the order
            $orderData = $request->validated();
            // Append the user_id to the order data
            $orderData['user_id'] = $userId;

            $order = Order::create($orderData);
            $orderDetailsHtml=$this->attachProductsToOrder($order, $request->input('cart'));

            $firebaseData = [
                'name' => $orderData['fname'],
                'company' => $orderData['company_name'],
                'contact' => $orderData['phone_num'],
//                'order_detail' => $orderData['order_detail'],
                'created_at' =>now()->toDateTimeString(),
                'to' => "rahatuddin786@gmail.com",
                'replyTo' => $orderData['email'],
                'message' => [
                    'subject' => "---3aFood Order--- ",
                    'html' => "<b>Name:</b> " . $orderData['fname'] .
                        "<br><b>Company:</b> " . $orderData['company_name'] .
                        "<br><b>Contact:</b> " . $orderData['phone_num'].
                        "<br><br><b>Order Details:</b><br>" . $orderDetailsHtml,

                ],
            ];

            // Store data in Firebase
            $firebaseKey = $this->firebaseService->sendEmail($firebaseData);
            // Attach products to the order

            return ResponseHelper::success($order, 'Order placed successfully', 201);
        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to place order', 500, $e->getMessage());
        }
    }
    private function attachProductsToOrder(Order $order, array $cart)
    {
        $orderDetailsHtml = "<table border='1'>
                            <tr>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Product Price</th>
                            </tr>";

        foreach ($cart as $item) {
            // Validate if the product exists
            $product = Product::findOrFail($item['product_id']);
            $orderDetailsHtml .= "<tr>
                                <td>" . $product->title . "</td>
                                <td>" . $item['quantity'] . "</td>
                                <td>" . ($item['price'] != 0 ? $item['price'] : "") . "</td>
                              </tr>";

            $order->products()->attach($product->id, [
                'quantity' => $item['quantity'],
                'price' => $item['price'], // Use the provided price from the request
            ]);
        }
        $orderDetailsHtml .= "</table>";
        return $orderDetailsHtml;
    }




    public function getUserOrderHistory()
    {
        try {
            // Get the authenticated user's ID
            $userId = Auth::id();

            // Retrieve the order history for the user
            $orderHistory = Order::where('user_id', $userId)
                ->with(['products' => function ($query) {
                    $query->select('products.id', 'products.title')
                        ->withPivot(['quantity', 'price']);
                }])
                ->get();

            $transformedOrderHistory = $orderHistory->map(function ($order) {
                return [
                    'fname' => $order->fname,
                    'lname' => $order->lname,
                    'company_name' => $order->company_name,
                    'address' => $order->address,
                    'phone_num' => $order->phone_num,
                    'email' => $order->email,
                    'additional_info' => $order->additional_info,
                    'total_price' => $order->total_price,
                    'status' => $order->status,
                    'created_at' => $order->created_at,
                    'updated_at' => $order->updated_at,
                    'products' => $order->products->map(function ($product) {
                        return [
                            'title' => $product->title,
                            'quantity' => $product->pivot->quantity,
                            'price' => $product->pivot->price,
                        ];
                    }),
                ];
            });



            return ResponseHelper::success(  $transformedOrderHistory, 'User order history retrieved successfully', 200);
        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to retrieve user order history', 500, $e->getMessage());
        }
    }





    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getOrdersByTypeAndStatus(string $type, string $status)
    {
        try {
            $validatedData = validator([
                'type' => $type,
                'status' => $status,
            ], [
                'type' => ['required', Rule::in(['retail', 'wholesale'])],
                'status' => ['required', Rule::in(['draft', 'pending', 'completed'])],
            ]);

            if ($validatedData->fails()) {
                throw new ValidationException($validatedData);
            }

            $orders = Order::where('type', $type)
                ->where('status', $status)
                ->get();

            return ResponseHelper::success($orders, 'Orders retrieved successfully', 200);
        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to retrieve orders', 500, $e->getMessage());
        }
    }

    public function completeOrder(string $order_id)
    {
        try {
            // Find the order by ID
            $order = Order::find($order_id);

            // Check if the order exists
            if (!$order) {
                return ResponseHelper::error("Order with ID $order_id not found", 404);
            }

            // Check if the order is in pending status
            if ($order->status !== 'pending') {
                return ResponseHelper::error("Order with ID $order_id is not in pending status", 422);
            }

            // Update the order status to completed
            $order->update(['status' => 'completed']);

            return ResponseHelper::success([], 'Order status updated to completed', 200);
        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to update order status', 500, $e->getMessage());
        }
    }




}
