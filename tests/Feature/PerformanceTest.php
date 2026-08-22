<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Project;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PerformanceTest extends TestCase
{
    use RefreshDatabase;

    protected $thresholdMs = 200;

    protected function checkResponseTime(string $endpoint, callable $setupData)
    {
        $setupData();

        $startTime = microtime(true);

        $response = $this->getJson($endpoint);

        $executionTimeMs = (microtime(true) - $startTime) * 1000;

        $response->assertStatus(200);

        $this->assertLessThan(
            $this->thresholdMs,
            $executionTimeMs,
            "Performance Warning: Endpoint [{$endpoint}] took too long ({$executionTimeMs} ms)"
        );
    }

    public function test_categories_api_performance(): void
    {
        $this->checkResponseTime('/api/v1/categories', function () {
            Category::factory()->count(50)->create();
        });
    }

    public function test_products_api_performance(): void
    {
        $this->checkResponseTime('/api/v1/products', function () {
            Category::factory()->create();
            Product::factory()->count(50)->create();
        });
    }

    public function test_projects_api_performance(): void
    {
        $this->checkResponseTime('/api/v1/projects', function () {
            Project::factory()->count(50)->create();
        });
    }

    public function test_settings_api_performance(): void
    {
        $this->checkResponseTime('/api/v1/settings', function () {
            for ($i = 0; $i < 10; $i++) {
                Setting::create([
                    'key' => "site_setting_{$i}",
                    'value' => "Value {$i}",
                ]);
            }
        });
    }
}
