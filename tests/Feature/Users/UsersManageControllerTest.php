<?php

namespace Tests\Feature\Users;

use App\Helpers\PermissionHelper;
use App\Helpers\UserHelper;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UsersManageControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        PermissionHelper::create_permissions();
        PermissionHelper::create_series_management_permissions();
        PermissionHelper::create_user_management_permissions();
        PermissionHelper::define_gates();
    }

    #[Test]
    public function loginAsVideoManager()
    {
        // Arrange: Crear permisos i un usuari amb permís de 'manage users'
        PermissionHelper::create_user_management_permissions();
        $user = UserHelper::create_video_manager_user();
        $user->givePermissionTo('manage users');
        $user->givePermissionTo('users.create');
        $user->givePermissionTo('users.edit');
        $user->givePermissionTo('users.delete');

        // Act: Autenticar l'usuari
        $this->actingAs($user);

        // Assert: Verificar que l'usuari està autenticat
        $this->assertAuthenticatedAs($user);
    }

    #[Test]
    public function loginAsSuperAdmin()
    {
        // Arrange: Crear permisos i un usuari amb rol de 'superadmin'
        PermissionHelper::create_user_management_permissions();
        $user = UserHelper::create_superadmin_user();
        $user->givePermissionTo('manage users');
        $user->givePermissionTo('users.create');
        $user->givePermissionTo('users.edit');
        $user->givePermissionTo('users.delete');

        // Act: Autenticar l'usuari
        $this->actingAs($user);

        // Assert: Verificar que l'usuari està autenticat
        $this->assertAuthenticatedAs($user);
    }

    #[Test]
    public function loginAsRegularUser()
    {
        // Arrange: Crear un usuari sense permisos especials
        $user = UserHelper::create_regular_user();

        // Act: Autenticar l'usuari
        $this->actingAs($user);

        // Assert: Verificar que l'usuari està autenticat
        $this->assertAuthenticatedAs($user);
    }

    #[Test]
    public function user_with_permissions_can_see_add_users()
    {
        // Arrange: Autenticar com a superadmin (té permisos per afegir usuaris)
        $this->loginAsSuperAdmin();

        // Act: Accedir a la pàgina d'afegir usuaris
        $response = $this->get('/usersmanage/create');

        // Assert: Verificar que la pàgina es carrega correctament
        $response->assertStatus(200);
    }

    #[Test]
    public function user_without_users_manage_create_cannot_see_add_users()
    {
        // Arrange: Autenticar com a usuari regular (sense permisos)
        $this->loginAsRegularUser();

        // Act: Accedir a la pàgina d'afegir usuaris
        $response = $this->get('/usersmanage/create');

        // Assert: Verificar que l'accés està prohibit
        $response->assertStatus(403);
    }

    #[Test]
    public function user_with_permissions_can_store_users()
    {
        // Arrange: Autenticar com a superadmin (té permisos per emmagatzemar usuaris)
        $this->loginAsSuperAdmin();

        $newUserData = [
            'name' => 'Nou Usuari',
            'email' => 'nou@example.com',
            'password' => 'password',
            'roles'    => ['superadmin']
        ];

        // Act: Emmagatzemar un nou usuari
        $response = $this->post('/usersmanage', $newUserData);

        // Assert: Verificar que l'usuari s'ha creat i es redirigeix correctament
        $response->assertRedirect('/usersmanage');
        $this->assertDatabaseHas('users', ['email' => 'nou@example.com']);

        // Assert: Verificar que la contrasenya s'ha hashejat correctament
        $createdUser = User::where('email', 'nou@example.com')->first();
        $this->assertTrue(\Hash::check('password', $createdUser->password));
    }

    #[Test]
    public function user_without_permissions_cannot_store_users()
    {
        // Arrange: Autenticar com a usuari regular (sense permisos)
        $this->loginAsRegularUser();

        $newUserData = [
            'name' => 'Nou Usuari',
            'email' => 'nou@example.com',
            'password' => 'password',
        ];

        // Act: Intentar emmagatzemar un nou usuari
        $response = $this->post('/usersmanage', $newUserData);

        // Assert: Verificar que l'accés està prohibit
        $response->assertStatus(403);
    }

    #[Test]
    public function user_with_permissions_can_destroy_users()
    {
        // Arrange: Autenticar com a superadmin (té permisos per eliminar usuaris)
        $this->loginAsSuperAdmin();

        $userToDelete = User::factory()->create();

        // Act: Eliminar l'usuari
        $response = $this->delete("/usersmanage/{$userToDelete->id}");

        // Assert: Verificar que l'usuari s'ha eliminat i es redirigeix correctament
        $response->assertRedirect('/usersmanage');
        $this->assertDatabaseMissing('users', ['id' => $userToDelete->id]);
    }

    #[Test]
    public function user_without_permissions_cannot_destroy_users()
    {
        // Arrange: Autenticar com a usuari regular (sense permisos)
        $this->loginAsRegularUser();

        $userToDelete = User::factory()->create();

        // Act: Intentar eliminar l'usuari
        $response = $this->delete("/usersmanage/{$userToDelete->id}");

        // Assert: Verificar que l'accés està prohibit
        $response->assertStatus(403);
    }

    #[Test]
    public function user_with_permissions_can_see_edit_users()
    {
        // Arrange: Autenticar com a superadmin (té permisos per editar usuaris)
        $this->loginAsSuperAdmin();

        $userToEdit = User::factory()->create();

        // Act: Accedir a la pàgina d'edició d'usuaris
        $response = $this->get("/usersmanage/{$userToEdit->id}/edit");

        // Assert: Verificar que la pàgina es carrega correctament
        $response->assertStatus(200);
    }

    #[Test]
    public function user_without_permissions_cannot_see_edit_users()
    {
        // Arrange: Autenticar com a usuari regular (sense permisos)
        $this->loginAsRegularUser();

        $userToEdit = User::factory()->create();

        // Act: Intentar accedir a la pàgina d'edició d'usuaris
        $response = $this->get("/usersmanage/{$userToEdit->id}/edit");

        // Assert: Verificar que l'accés està prohibit
        $response->assertStatus(403);
    }

    #[Test]
    public function user_with_permissions_can_update_users()
    {
        // Arrange: Autenticar com a superadmin (té permisos per actualitzar usuaris)
        $this->loginAsSuperAdmin();

        $userToUpdate = User::factory()->create();
        $updatedData = [
            'name' => 'Usuari Actualitzat',
            'email' => $userToUpdate->email, // Mantenir el mateix email per evitar errors de validació
            'roles' => ['superadmin'], // afegim el camp roles
        ];

        // Act: Actualitzar l'usuari
        $response = $this->put("/usersmanage/{$userToUpdate->id}", $updatedData);

        // Assert: Verificar que l'usuari s'ha actualitzat i es redirigeix correctament
        $response->assertRedirect('/usersmanage');
        $this->assertDatabaseHas('users', ['name' => 'Usuari Actualitzat']);
    }

    #[Test]
    public function user_without_permissions_cannot_update_users()
    {
        // Arrange: Autenticar com a usuari regular (sense permisos)
        $this->loginAsRegularUser();

        $userToUpdate = User::factory()->create();
        $updatedData = ['name' => 'Usuari Actualitzat'];

        // Act: Intentar actualitzar l'usuari
        $response = $this->put("/usersmanage/{$userToUpdate->id}", $updatedData);

        // Assert: Verificar que l'accés està prohibit
        $response->assertStatus(403);
    }

    #[Test]
    public function user_with_permissions_can_manage_users()
    {
        // Arrange: Autenticar com a superadmin (té permisos per gestionar usuaris)
        $this->loginAsSuperAdmin();

        // Act: Accedir a la pàgina de gestió d'usuaris
        $response = $this->get('/usersmanage');

        // Assert: Verificar que la pàgina es carrega correctament
        $response->assertStatus(200);
    }

    #[Test]
    public function regular_users_cannot_manage_users()
    {
        // Arrange: Autenticar com a usuari regular (sense permisos)
        $this->loginAsRegularUser();

        // Act: Intentar accedir a la pàgina de gestió d'usuaris
        $response = $this->get('/usersmanage');

        // Assert: Verificar que l'accés està prohibit
        $response->assertStatus(403);
    }

    #[Test]
    public function guest_users_cannot_manage_users()
    {
        // Act: Intentar accedir a la pàgina de gestió d'usuaris sense autenticar
        $response = $this->get('/usersmanage');

        // Assert: Verificar que es redirigeix a la pàgina de login
        $response->assertRedirect('/login');
    }

    #[Test]
    public function superadmins_can_manage_users()
    {
        // Arrange: Autenticar com a superadmin (té permisos per gestionar usuaris)
        $this->loginAsSuperAdmin();

        // Act: Accedir a la pàgina de gestió d'usuaris
        $response = $this->get('/usersmanage');

        // Assert: Verificar que la pàgina es carrega correctament
        $response->assertStatus(200);
    }
}
