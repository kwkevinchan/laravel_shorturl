<?php

namespace App\Validators\User;

use Illuminate\Http\Request;

class LoginValidation
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
            'email' => 'required|string|max:255',
            'password' => 'required|string',
        ]);
    }
}
