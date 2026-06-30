<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Video;
use App\Models\VideoAccess;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class VideoPermittingSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_dashboard()
    {
        $this->get('/dashboard')->assertRedirect(route('login'));
    }

    public function test_login_page_renders_and_handles_auth()
    {
        $admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->get('/login')->assertStatus(200);

        $response = $this->post('/login', [
            'email' => 'admin@test.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_admin_can_crud_customer()
    {
        $admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Create Customer
        $response = $this->actingAs($admin)->post(route('admin.customers.store'), [
            'name' => 'Customer A',
            'email' => 'customer.a@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.customers.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'customer.a@test.com',
            'role' => 'customer',
        ]);

        $customer = User::where('email', 'customer.a@test.com')->first();

        // Update Customer
        $response = $this->actingAs($admin)->put(route('admin.customers.update', $customer->id), [
            'name' => 'Customer A Updated',
            'email' => 'customer.a.new@test.com',
            'password' => '',
        ]);

        $response->assertRedirect(route('admin.customers.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'customer.a.new@test.com',
            'name' => 'Customer A Updated',
        ]);

        // Delete Customer
        $response = $this->actingAs($admin)->delete(route('admin.customers.destroy', $customer->id));
        $response->assertRedirect(route('admin.customers.index'));
        $this->assertDatabaseMissing('users', [
            'id' => $customer->id,
        ]);
    }

    public function test_admin_can_upload_and_delete_video()
    {
        Storage::fake('local');

        $admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $file = UploadedFile::fake()->create('sample.mp4', 500, 'video/mp4');

        // Store
        $response = $this->actingAs($admin)->post(route('admin.videos.store'), [
            'title' => 'Test Video',
            'description' => 'Test Description',
            'video' => $file,
        ]);

        $response->assertRedirect(route('admin.videos.index'));
        $this->assertDatabaseHas('videos', [
            'title' => 'Test Video',
        ]);

        $video = Video::first();
        Storage::disk('local')->assertExists($video->file_path);

        // Delete
        $response = $this->actingAs($admin)->delete(route('admin.videos.destroy', $video->id));
        $response->assertRedirect(route('admin.videos.index'));
        $this->assertDatabaseMissing('videos', [
            'id' => $video->id,
        ]);
        Storage::disk('local')->assertMissing($video->file_path);
    }

    public function test_customer_permitting_lifecycle()
    {
        Storage::fake('local');

        $admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $customer = User::create([
            'name' => 'Customer Test',
            'email' => 'customer@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        $video = Video::create([
            'title' => 'Sample Video',
            'description' => 'Sample Description',
            'file_path' => 'videos/sample.mp4',
        ]);

        // Mock video on local disk
        Storage::disk('local')->put('videos/sample.mp4', 'dummy video content');

        // 1. Customer Dashboard view (shows Locked or Request button)
        $response = $this->actingAs($customer)->get(route('customer.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Request Access');

        // 2. Customer requests access
        $response = $this->actingAs($customer)->post(route('customer.requests.request', $video->id));
        $response->assertRedirect();
        $this->assertDatabaseHas('video_accesses', [
            'user_id' => $customer->id,
            'video_id' => $video->id,
            'status' => 'pending',
        ]);

        $access = VideoAccess::first();

        // 3. Customer tries to watch or stream (403 Forbidden / redirect)
        $this->actingAs($customer)->get(route('customer.watch', $video->id))->assertRedirect(route('customer.dashboard'));
        $this->actingAs($customer)->get(route('video.stream', $video->id))->assertStatus(403);

        // 4. Admin approves access request with 2 minutes duration
        $response = $this->actingAs($admin)->post(route('admin.requests.approve', $access->id), [
            'duration' => 2,
            'duration_unit' => 'minutes',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('video_accesses', [
            'id' => $access->id,
            'status' => 'approved',
        ]);

        $access->refresh();
        $this->assertTrue($access->isActive());

        // 5. Customer can now watch and stream successfully
        $this->actingAs($customer)->get(route('customer.watch', $video->id))->assertStatus(200);
        $this->actingAs($customer)->get(route('video.stream', $video->id))->assertStatus(200);

        // 6. Time travel 3 minutes into the future (access expired)
        $this->travel(3)->minutes();

        // 7. Try to stream again (403 Forbidden because it's expired)
        $this->actingAs($customer)->get(route('video.stream', $video->id))->assertStatus(403);
        
        // 8. Try to access watch page (redirects to dashboard)
        $this->actingAs($customer)->get(route('customer.watch', $video->id))->assertRedirect(route('customer.dashboard'));
    }
}
