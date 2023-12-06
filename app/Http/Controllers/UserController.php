<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserController extends Controller
{

    protected $firebaseService;
    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    public function register(UserRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $validatedData['password'] = Hash::make($validatedData['password']);
            $user = User::create($validatedData);
            // Log in the user
//            Auth::login($user);
            // Generate and attach an API token
            $token = $user->createToken($user->email)->plainTextToken;
            return ResponseHelper::success(['name' => $user->name, 'user_type' => $user->usertype,'role' => 'user', 'token' => $token],"User registered and logged in successfully",201);
        }
        catch (\Exception $e) {
            return ResponseHelper::error('Failed to register user',500,$e->getMessage());
        }
    }

    public function login(Request $request)
    {
        try {
            $credentials =  $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $token = $user->createToken($user->email)->plainTextToken;
                $role = $user->hasRole('admin') ? 'admin' : 'user';
                return ResponseHelper::success(['name' => $user->name, 'user_type' => $user->usertype, 'role' => $role, 'token' => $token],'User logged in successfully', 200);
            }
            return ResponseHelper::error( 'Invalid credentials', 401);
        } catch (\Exception $e) {
            return ResponseHelper::error( 'Failed to log in',500, $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json(['message' => 'Logout successful'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to logout', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $users = User::all();
            return ResponseHelper::success($users, 'Users retrieved successfully', 200);
        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to retrieve users', 500, $e->getMessage());
        }
    }

    public function show()
    {
        try {
            $user = User::find(Auth::id());
            $userData = [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
            ];
            return ResponseHelper::success( $userData, 'User retrieved successfully', 200);
        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to retrieve user', 500, $e->getMessage());
        }
    }

    public function getUsersByUsertype($usertype)
    {
        try {
            // Validate usertype
            $validator = validator(['usertype' => $usertype], [
                'usertype' => ['required', Rule::in(['retail', 'wholesale'])],
            ]);

            if ($validator->fails()) {
                return ResponseHelper::error('The usertype is invalid', 422, $validator->errors()->first());
            }

            // If validation passes, retrieve users
            $users = User::where('usertype', $usertype)->get();

            return ResponseHelper::success($users, 'Users retrieved by usertype successfully', 200);
        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to retrieve users by usertype', 500, $e->getMessage());
        }
    }

    public function updateAccountInfo(UserRequest $request)
    {
        try {
            $user = Auth::user();

            // Validate the request, including the current password validation for updates
            $data = $request->validated();

            // Update the user information
            $user->update($data);
            $userData = [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
            ];
            return ResponseHelper::success( $userData , 'User account information updated successfully', 200);
        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to update user account information', 500, $e->getMessage());
        }
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        try {
            // Get the authenticated user's ID
            $userId = Auth::id();
            $user = User::find($userId);
            $data = $request->validated();
            // Update the user's password with the new password
            $user->password = Hash::make($data['new_password']);
            $user->save();
            return ResponseHelper::success([], 'Password changed successfully', 200);
        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to change password', 500, $e->getMessage());
        }
    }

    public function resetPassword(Request $request)
    {
        // Validate request data
        $request->validate([
            'email' => [
                'required',
                'email',
                function ($attribute, $value, $fail) {
                    if (!User::where('email', $value)->exists()) {
                        $fail('No user found for the given email.');
                    }
                },
            ],
        ]);

        // Generate a random 8-character password
        $newPassword = Str::random(8);

        // Find the user by email
        $user = User::where('email', $request->email)->first();

        // Update user password with the new one
        $user->update([
            'password' => Hash::make($newPassword),
        ]);

        // Prepare data for Firebase email
        $data = [
            'type'=>'Reset Password',
            'to' =>  $request->email,
            'message' => [
                'subject' => "---3aFood- Reset Password--- ",
                'html' => "<b>Email:</b> " .$request->email.
                    "<br><b>Password:</b> " . $newPassword

            ],

        ];

        // Send email using Firebase service
        $this->firebaseService->sendEmail($data);
        // Send success response
        return ResponseHelper::success([],'New password sent to your email', 200);
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
    public function destroy($user_id)
    {
        try {
            $user = User::find($user_id);

            if (!$user) {
                return ResponseHelper::error("User with ID $user_id not found", 404);
            }

            $user->delete();

            return ResponseHelper::success([], 'User deleted successfully', 200);
        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to delete user', 500, $e->getMessage());
        }
    }
}
