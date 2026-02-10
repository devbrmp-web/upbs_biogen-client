<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_sync_payment_status_proxies_to_admin_api(): void
    {
        config(['app.url_dev_admin' => 'https://admin.example']);

        Http::fake([
            'https://admin.example/api/orders/payment/sync' => Http::response([
                'success' => true,
                'order' => ['order_code' => 'ORD-001'],
            ], 200),
        ]);

        $response = $this->postJson('/orders/payment/sync', [
            'order_id' => 'ORD-001',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
    }
}
