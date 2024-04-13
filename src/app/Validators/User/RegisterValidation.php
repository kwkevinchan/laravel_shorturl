<?php

namespace App\Validators\User;

use Illuminate\Http\Request;

class RegisterValidation
{
    /**
     * Validate the user registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public static function validate(Request $request)
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);
    }
}
