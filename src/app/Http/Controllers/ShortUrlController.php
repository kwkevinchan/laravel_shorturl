<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\ShortUrl;
use Illuminate\Support\Facades\Config;
use App\Validators\ShortUrl\GenerateShortUrlValidation;
use Illuminate\Support\Facades\Cache;

class ShortUrlController extends Controller
{
    /**
     * Generate a short URL for the given original URL.
     *
     * @param  string  $originalUrl
     * @return string|null
     */
    public function generateShortUrl(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = GenerateShortUrlValidation::validate($request);

            $shortUrl = ShortUrl::where('original_url', $validatedData['url'])->first();
            if ($shortUrl) {
                return response()->json([
                    'short_url' => Config::get('app.url') . '/' . $shortUrl->short_code
                ]);
            }

            // generateShortCode
            while (true) {
                $shortCode = Str::random(Config::get('shorturl.length'));
                if (!ShortUrl::where('short_code', $shortCode)->exists()) {
                    break;
                }
            }

            $shortUrl = new ShortUrl();
            $shortUrl->original_url = $validatedData['url'];
            $shortUrl->short_code = $shortCode;
            $shortUrl->save();

            return response()->json([
                'short_url' => Config::get('app.url') . '/' . $shortUrl->short_code
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }


    /**
     * Redirect to the original URL associated with the given short URL.
     *
     * @param  string  $shortUrl
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToOriginalUrl($shortCode)
    {
        try {
            // check Cache
            $shortUrl = Cache::get('key');
            if ($shortUrl) {
                return redirect()->away($shortUrl);
            }

            $shortUrl = ShortUrl::where('short_code', $shortCode)->first();

            if ($shortUrl) {
                Cache::put('key', $shortUrl->original_url, now()->addDay());
                return redirect()->away($shortUrl->original_url);
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
