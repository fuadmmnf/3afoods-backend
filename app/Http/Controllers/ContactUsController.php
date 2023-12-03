<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\ContactUsRequest;
use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    public function submit(ContactUsRequest $request)
    {
        // For example, you can access validated data like this:
        $data = $request->validated();

        // Perform actions with the data (send email, store in database, etc.)

        // Return a response (success or error)
        return ResponseHelper::success($data,'Contact form submitted successfully',200);
    }
}
