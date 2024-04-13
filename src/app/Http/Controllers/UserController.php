<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Validators\User\RegisterValidation;
use App\Validators\User\LoginValidation;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class UserController extends Controller
{
    /**
     * Register a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = RegisterValidation::validate($request);

            // Create a new user
            $user = new User();
            $user->name = $validatedData['name'];
            $user->email = $validatedData['email'];
            $user->password = Hash::make($validatedData['password']);
            $user->save();

            // Return a success response
            return response()->json(['message' => 'User registered successfully']);
            // user exists
        } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
            return response()->json(['message' => 'User already exists'], 400);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Login API.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {
            // Validate the request data
            LoginValidation::validate($request);

            // Attempt to authenticate the user
            if (Auth::attempt([
                'email' => $request->email,
                'password' => $request->password
            ])) {
                $user = Auth::user();
                $token = $user->createToken(Config::get('app.name'))->accessToken;
         
                return response()->json(['token' => $token], 200);
            }

            return response()->json(['error' => 'Unauthorized'], 401);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
