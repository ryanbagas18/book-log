<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/admin')->assertRedirect(route('login'));
        $this->post('/logout')->assertRedirect(route('login'));
    }

    public function test_user_can_register_and_is_redirected_to_dashboard(): void
    {
        $response = $this->post('/register', [
            'name' => 'New User',
            'email' => 'new@example.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard.index'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'new@example.test', 'role' => 'customer']);
    }

    public function test_posting_role_admin_on_register_has_no_effect(): void
    {
        $this->post('/register', [
            'name' => 'Sneaky Admin',
            'email' => 'sneaky@example.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin',
        ]);

        $user = User::where('email', 'sneaky@example.test')->firstOrFail();
        $this->assertSame('customer', $user->role);
        $this->assertFalse($user->isAdmin());
    }

    public function test_customer_login_redirects_to_dashboard(): void
    {
        User::factory()->create([
            'email' => 'customer@example.test',
            'password' => 'password',
        ]);

        $response = $this->post('/login', [
            'email' => 'customer@example.test',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard.index'));
        $this->assertAuthenticated();
    }

    public function test_admin_login_redirects_to_admin_dashboard(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.test',
            'password' => 'password',
        ]);
        $admin->role = 'admin';
        $admin->save();

        $response = $this->post('/login', [
            'email' => 'admin@example.test',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_login_fails_with_wrong_password(): void
    {
        User::factory()->create(['email' => 'user@example.test', 'password' => 'password']);

        $response = $this->post('/login', [
            'email' => 'user@example.test',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_user_can_log_out(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
