<?php

namespace Tests\Unit;

use App\Helpers\UserHelper;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_is_super_admin()
    {
        // Arrange
        $superadmin = UserHelper::create_superadmin_user();
        $regular = UserHelper::create_regular_user();

        // Act & Assert
        $this->assertTrue($superadmin->isSuperAdmin(), 'El superadmin ha de retornar true en isSuperAdmin()');
        $this->assertFalse($regular->isSuperAdmin(), 'Un usuari regular ha de retornar false en isSuperAdmin()');
    }
}
