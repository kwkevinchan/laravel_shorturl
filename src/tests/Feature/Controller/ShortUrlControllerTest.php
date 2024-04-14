<?php

namespace Tests\Feature\Controller;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use App\Models\ShortUrl;
use App\Http\Controllers\ShortUrlController;

class ShortUrlControllerTest extends TestCase
{
    use RefreshDatabase;

    private $shortUrlController;
    private $originalUrl = 'https://www.google.com';
    private $shortUrl;

    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->shortUrlController = new ShortUrlController();
    }

    public function test_generateShortUrl(): void
    {
        $this->generateShortUrl();
        $this->checkDate();
        $this->redirectToOriginalUrl();
        $this->regenerateShortUrl();
    }

    private function generateShortUrl(): void
    {
        $response = $this->get('/shorturl?url=' . $this->originalUrl);
        $response->assertStatus(200);
        $response->assertJsonStructure(['short_url']);
        $this->shortUrl = $response->getData()->short_url;
    }

    private function checkDate(): void
    {
        $shortUrlRecord = ShortUrl::where('original_url', $this->originalUrl)->first();
        $this->assertNotNull($shortUrlRecord);
        $this->assertEquals($this->shortUrl, config('app.url') . '/' . $shortUrlRecord->short_code);
    }

    private function redirectToOriginalUrl(): void
    {

        $shortCode = str_replace(config('app.url') . '/', '', $this->shortUrl);

        $response = $this->get('/' . $shortCode);
        $response->assertStatus(302);
        $response->assertRedirect($this->originalUrl);
    }

    private function regenerateShortUrl(): void
    {
        $response = $this->get('/shorturl?url=' . $this->originalUrl);
        $response->assertStatus(200);
        $response->assertJsonStructure(['short_url']);
        $this->assertEquals($this->shortUrl, $response->getData()->short_url);
    }
}
