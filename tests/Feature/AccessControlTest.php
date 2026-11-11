<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AccessControlTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_gets_403_on_admin_routes(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/admin')->assertForbidden();
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->create();
        $admin->role = 'admin';
        $admin->save();

        $this->actingAs($admin)->get('/admin')->assertOk();
    }

    public function test_guest_is_redirected_from_admin_routes(): void
    {
        $this->get('/admin')->assertRedirect(route('login'));
    }

    public function test_profile_complete_middleware_redirects_incomplete_profile(): void
    {
        Route::get('/profile-gate', fn () => 'ok')
            ->middleware(['auth', 'profile.complete']);

        $incomplete = User::factory()->create(['phone' => null, 'address' => null]);
        $complete = User::factory()->create([
            'phone' => '+628123456789',
            'address' => 'Jakarta',
        ]);

        $this->actingAs($incomplete)
            ->get('/profile-gate')
            ->assertRedirect(route('profile.edit'));

        $this->actingAs($complete)->get('/profile-gate')->assertOk();
    }

    public function test_profile_update_validates_phone_format(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->put('/profile', [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => 'not-a-phone',
            'address' => 'Somewhere',
        ])->assertSessionHasErrors('phone');

        $this->actingAs($user)->put('/profile', [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => '+628123456789',
            'address' => 'Somewhere',
        ])->assertSessionHasNoErrors();

        $this->assertSame('+628123456789', $user->fresh()->phone);
    }
}
