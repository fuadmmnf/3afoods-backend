<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

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
            return ResponseHelper::success(['email' => $user->email, 'user_type' => $user->usertype, 'token' => $token],"User registered and logged in successfully",201);
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

                return response()->json(['email' => $user->email, 'user_type' => $user->usertype, 'token' => $token, 'message' => 'User logged in successfully'], 200);
            }

            return response()->json(['message' => 'Invalid credentials'], 401);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to log in', 'error' => $e->getMessage()], 500);
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
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
}
