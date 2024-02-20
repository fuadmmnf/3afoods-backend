<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\ContactUsRequest;
use App\Services\FirebaseService;
use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    protected $firebaseService;
    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }
    public function submit(ContactUsRequest $request)
    {
        // For example, you can access validated data like this:
        $data = $request->validated();

        $firebaseData = [
            'type'=>'Contact Us Form',
            'created_at' =>now()->toDateTimeString(),
            'to' => "orders@3afoods.com.au",
            'replyTo' => $data ['email'],
            'message' => [
                'subject' => "---3aFood-Contact Us--- ",
                'html' => "<b>Name:</b> " . $data['name'].
                    "<br><b>Email:</b> " .$data['email'] .
                    "<br><b>Subject:</b> " .$data['subject'] .
                    "<br><pre><b>Message:</b> " .$data['message'] . "</pre>"
            ],
        ];
        $firebaseKey = $this->firebaseService->sendEmail($firebaseData);

        // Perform actions with the data (send email, store in database, etc.)

        // Return a response (success or error)
        return ResponseHelper::success($data,'Contact form submitted successfully',200);
    }
}
