<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\TenantModule;
use App\Models\User;
use App\Models\WaNotification;
use App\Support\Tenancy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class NotificationModuleTest extends TestCase
{
    use RefreshDatabase;

    protected Tenant $tenant;
    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::create([
            'name' => 'MTs Notifikasi',
            'domain' => 'mts-notif',
            'status' => 'active',
        ]);

        TenantModule::create([
            'tenant_id' => $this->tenant->id,
            'module_code' => 'Notification',
            'active' => true,
        ]);

        $this->admin = User::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Admin Notifikasi',
            'email' => 'admin@notif.mts',
            'phone' => '628000000088',
            'password' => bcrypt('password'),
        ]);

        app(Tenancy::class)->setTenant($this->tenant);
    }

    #[Test]
    public function connect_page_is_accessible_and_gets_status_from_gateway(): void
    {
        // Mock the HTTP call to the node.js gateway status endpoint
        Http::fake([
            '*/api/tenant/*/session/status' => Http::response(['status' => 'DISCONNECTED'], 200),
        ]);

        $response = $this->actingAs($this->admin)->get(route('notification.connect'));

        $response->assertOk();
        $response->assertSee('Koneksi WhatsApp Gateway');
    }

    #[Test]
    public function start_session_makes_http_requests_to_gateway(): void
    {
        Http::fake([
            '*/api/tenant' => Http::response(['success' => true], 200),
            '*/api/tenant/*/session/start' => Http::response(['status' => 'QR_READY', 'qr' => 'base64_string_here'], 200),
        ]);

        $response = $this->actingAs($this->admin)->postJson(route('notification.session.start'));

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'status' => 'QR_READY',
            'qr' => 'base64_string_here',
        ]);
    }

    #[Test]
    public function webhook_callback_updates_notification_status_successfully(): void
    {
        // Create a dummy notification record
        app(Tenancy::class)->setTenant($this->tenant);
        $notif = WaNotification::create([
            'tenant_id' => $this->tenant->id,
            'to_phone' => '628520000001',
            'type' => 'attendance',
            'payload' => ['student_name' => 'John Doe'],
            'status' => 'queued',
        ]);

        $secret = config('app.wa_callback_secret', 'dev-callback-secret');

        // Fire webhook call
        $response = $this->postJson(route('api.notification.delivery_callback'), [
            'tenantId' => (string) $this->tenant->id,
            'referenceId' => (string) $notif->id,
            'status' => 'delivered',
        ], [
            'X-Callback-Secret' => $secret,
        ]);

        $response->assertOk();
        $response->assertJson(['success' => true]);

        // Verify database was updated
        app(Tenancy::class)->setTenant($this->tenant);
        $this->assertEquals('sent', $notif->fresh()->status);
        $this->assertNotNull($notif->fresh()->sent_at);
    }

    #[Test]
    public function webhook_callback_without_secret_is_denied(): void
    {
        $response = $this->postJson(route('api.notification.delivery_callback'), [
            'referenceId' => '1',
            'status' => 'delivered',
        ]);

        $response->assertStatus(401);
    }
}
