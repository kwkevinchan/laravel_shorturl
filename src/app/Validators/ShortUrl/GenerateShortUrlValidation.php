<?php

namespace App\Validators\ShortUrl;

use Illuminate\Http\Request;

class GenerateShortUrlValidation
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
            'url' => 'required|url|max:4096',
        ]);
    }
}
