<?php

namespace Tests\Feature;

use App\honorario as Honorario;
use App\sub_menu;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FacturaControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_upload_factura_to_own_honorario()
    {
        Storage::fake('local');

        $user = $this->createUserWithAccess('/misHonorarios');
        $honorario = $this->createHonorario($user);
        $file = UploadedFile::fake()->create('factura.pdf', 20, 'application/pdf');

        $this->actingAs($user)
            ->put('/misFacturas/create/store', [
                'honorario' => $honorario->id,
                'file' => $file,
            ])
            ->assertRedirect('/misHonorarios');

        $honorario->refresh();

        $this->assertNotNull($honorario->factura);
        Storage::assertExists($honorario->factura);
    }

    public function test_user_can_view_factura_upload_form()
    {
        $user = $this->createUserWithAccess('/misHonorarios');
        $honorario = $this->createHonorario($user);

        $this->actingAs($user)
            ->get("/misFacturas/create/{$honorario->id}")
            ->assertOk()
            ->assertSee('Cargar factura')
            ->assertSee("Honorario asociado");
    }

    public function test_user_cannot_upload_factura_to_another_users_honorario()
    {
        Storage::fake('local');

        $owner = $this->createUser();
        $other = $this->createUserWithAccess('/misHonorarios');
        $honorario = $this->createHonorario($owner);
        $file = UploadedFile::fake()->create('factura.pdf', 20, 'application/pdf');

        $this->actingAs($other)
            ->put('/misFacturas/create/store', [
                'honorario' => $honorario->id,
                'file' => $file,
            ])
            ->assertForbidden();

        $this->assertNull($honorario->fresh()->factura);
    }

    public function test_owner_can_view_uploaded_honorario_factura()
    {
        Storage::fake('local');

        $user = $this->createUserWithAccess('/misHonorarios');
        $honorario = $this->createHonorario($user, ['factura' => 'app/facturas/test.pdf']);
        Storage::put($honorario->factura, 'test factura');

        $this->actingAs($user)
            ->get("/facturas/show/{$honorario->id}")
            ->assertOk();
    }

    public function test_admin_can_view_honorario_factura_file()
    {
        Storage::fake('local');

        $owner = $this->createUser();
        $admin = $this->createUserWithAccess('/honorarios');
        $honorario = $this->createHonorario($owner, ['factura' => 'app/facturas/admin.pdf']);
        Storage::put($honorario->factura, 'admin factura');

        $this->actingAs($admin)
            ->get("/facturas/show/{$honorario->id}")
            ->assertOk();
    }

    public function test_owner_can_delete_honorario_factura()
    {
        Storage::fake('local');

        $user = $this->createUserWithAccess('/misHonorarios');
        $honorario = $this->createHonorario($user, ['factura' => 'app/facturas/delete.pdf']);
        Storage::put($honorario->factura, 'delete factura');

        $this->actingAs($user)
            ->delete("/facturas/delete/{$honorario->id}")
            ->assertRedirect('/misFacturas');

        $this->assertNull($honorario->fresh()->factura);
        Storage::assertMissing('app/facturas/delete.pdf');
    }

    public function test_user_can_view_own_honorarios_list()
    {
        $user = $this->createUserWithAccess('/misHonorarios');
        $this->createHonorario($user);

        $this->actingAs($user)
            ->get('/misHonorarios')
            ->assertOk()
            ->assertSee('Mis honorarios')
            ->assertSee('Liquidaciones disponibles');
    }

    public function test_user_can_view_own_facturas_list()
    {
        $user = $this->createUserWithAccess('/misHonorarios');
        $this->createHonorario($user, ['factura' => 'app/facturas/list.pdf']);

        $this->actingAs($user)
            ->get('/misFacturas')
            ->assertOk()
            ->assertSee('Mis facturas')
            ->assertSee('Facturas cargadas');
    }

    public function test_admin_can_view_honorarios_list()
    {
        $owner = $this->createUser();
        $admin = $this->createUserWithAccess('/honorarios');
        $this->createHonorario($owner, ['factura' => 'app/facturas/admin-list.pdf']);

        $this->actingAs($admin)
            ->get('/honorarios')
            ->assertOk()
            ->assertSee('Honorarios cargados')
            ->assertSee('Liquidaciones');
    }

    public function test_admin_can_view_facturas_list()
    {
        $owner = $this->createUser();
        $admin = $this->createUserWithAccess('/honorarios');
        $this->createHonorario($owner, ['factura' => 'app/facturas/admin-list.pdf']);

        $this->actingAs($admin)
            ->get('/facturas/show')
            ->assertOk()
            ->assertSee('Facturas cargadas')
            ->assertSee('Archivo de facturas');
    }

    private function createHonorario(User $user, array $overrides = []): Honorario
    {
        $honorario = new Honorario();
        $honorario->user_id = $user->id;
        $honorario->fecha = '2026-06-20';
        $honorario->file = 'app/honorarios/test.pdf';

        foreach ($overrides as $key => $value) {
            $honorario->{$key} = $value;
        }

        $honorario->save();

        return $honorario;
    }

    private function createUserWithAccess(string $url): User
    {
        $user = $this->createUser();

        $subMenu = sub_menu::where('url', $url)->firstOrFail();
        $user->access()->attach($subMenu->id);

        return $user;
    }

    private function createUser(): User
    {
        $user = new User();
        $user->code = uniqid('TST');
        $user->name = 'Test User';
        $user->doc = uniqid('doc');
        $user->email = uniqid('test') . '@example.com';
        $user->password = Hash::make('password');
        $user->specialty = 1;
        $user->type = 1;
        $user->dedication = 1;
        $user->save();

        return $user;
    }
}
