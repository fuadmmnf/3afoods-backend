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
            'to' => "rahatuddin786@gmail.com",
            'replyTo' => $data ['email'],
            'message' => [
                'subject' => "---3aFood-Contact Us--- ",
                'html' => "<b>Name:</b> " . $data['name'].
                    "<br><b>Email:</b> " .$data['email'] .
                    "<br><b>Subject:</b> " .$data['subject'] .
                    "<br><b>Contat Info:</b> " .$data['message']
            ],
        ];
        $firebaseKey = $this->firebaseService->sendEmail($firebaseData);

        // Perform actions with the data (send email, store in database, etc.)

        // Return a response (success or error)
        return ResponseHelper::success($data,'Contact form submitted successfully',200);
    }
}
