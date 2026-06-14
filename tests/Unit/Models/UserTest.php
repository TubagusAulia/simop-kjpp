<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_has_correct_fillable_attributes(): void
    {
        $user = new User();

        $this->assertEquals([
            'name',
            'username',
            'password',
            'role',
            'profile_photo',
        ], $user->getFillable());
    }

    public function test_user_password_is_hashed(): void
    {
        $user = User::factory()->create(['password' => 'secret123']);

        $this->assertNotEquals('secret123', $user->password);
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('secret123', $user->password));
    }

    public function test_user_has_hidden_password_and_remember_token(): void
    {
        $user = User::factory()->make();

        $this->assertContains('password', $user->getHidden());
        $this->assertContains('remember_token', $user->getHidden());
    }

    public function test_user_is_admin_returns_true_for_admin_role(): void
    {
        $user = User::factory()->make(['role' => 'admin']);

        $this->assertTrue($user->isAdmin());
        $this->assertFalse($user->isKaryawan());
        $this->assertFalse($user->isClient());
        $this->assertFalse($user->isMitra());
    }

    public function test_user_is_karyawan_returns_true_for_karyawan_role(): void
    {
        $user = User::factory()->make(['role' => 'karyawan']);

        $this->assertTrue($user->isKaryawan());
        $this->assertFalse($user->isAdmin());
    }

    public function test_user_is_client_returns_true_for_client_role(): void
    {
        $user = User::factory()->make(['role' => 'client']);

        $this->assertTrue($user->isClient());
        $this->assertFalse($user->isAdmin());
    }

    public function test_user_is_mitra_returns_true_for_mitra_role(): void
    {
        $user = User::factory()->make(['role' => 'mitra']);

        $this->assertTrue($user->isMitra());
        $this->assertFalse($user->isAdmin());
    }

    public function test_user_profile_photo_url_returns_default_when_no_photo(): void
    {
        $user = User::factory()->make(['profile_photo' => null]);

        $this->assertStringContainsString('profile-user.svg', $user->profile_photo_url);
    }

    public function test_user_profile_photo_url_returns_storage_path_when_photo_exists(): void
    {
        $user = User::factory()->make(['profile_photo' => 'photos/test.png']);

        $this->assertStringContainsString('storage/photos/test.png', $user->profile_photo_url);
    }
}
