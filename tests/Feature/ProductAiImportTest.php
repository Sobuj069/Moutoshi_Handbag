<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Setting;
use App\Services\ProductAiImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductAiImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_gemini_connection_and_import()
    {
        $testKey = env('GEMINI_API_KEY', config('services.gemini.api_key'));
        if (!$testKey) {
            $this->markTestSkipped('GEMINI_API_KEY is not configured in .env.');
        }

        Setting::set('gemini_api_key', $testKey);
        Category::create(['name' => 'Tote Bags', 'slug' => 'tote-bags']);

        $service = new ProductAiImportService();
        $conn = $service->testConnection();
        $this->assertTrue($conn['success'], $conn['message'] ?? 'Failed to connect');

        // Test with real Pinterest link
        $result = $service->importFromUrl('https://www.pinterest.com/pin/99360735523397542/');
        $this->assertTrue($result['success'], $result['message'] ?? 'Import failed');
        $this->assertNotEmpty($result['data']['title']);
        $this->assertGreaterThan(0, $result['data']['price']);
        $this->assertNotEmpty($result['data']['primary_image_path']);
        $this->assertGreaterThanOrEqual(300, $result['data']['primary_image_width']);
        $this->assertGreaterThanOrEqual(300, $result['data']['primary_image_height']);
    }
}
