<?php

namespace Tests\Feature;

use App\espacio;
use App\Mail\resolucion_espacio;
use App\Mail\solicitud_espacio;
use App\SolicitudEspacio;
use App\sub_menu;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SolicitudEspacioControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_space_request_when_slot_is_available()
    {
        Mail::fake();

        $user = $this->createUserWithAccess('/espacios/solicitudes/list');
        $this->createUserWithAccess('/espacios/solicitudes/autorizar');
        $space = $this->createSpace();

        $this->actingAs($user)
            ->put('/espacios/solicitudes/new/create', [
                'espacio' => $space->id,
                'fecha' => '2026-06-20',
                'horario' => 'Mañana (8:00 - 12:00)',
                'detalle' => 'Necesito el consultorio',
            ])
            ->assertOk()
            ->assertViewHas('result', true);

        $this->assertDatabaseHas('espacios_pivot', [
            'user_id' => $user->id,
            'espacio_id' => $space->id,
            'fecha' => '2026-06-20',
            'horario' => 'Mañana (8:00 - 12:00)',
            'autorizado' => SolicitudEspacio::PENDING,
        ]);
        Mail::assertSent(solicitud_espacio::class, 1);
    }

    public function test_user_cannot_create_space_request_when_slot_is_already_approved()
    {
        Mail::fake();

        $user = $this->createUserWithAccess('/espacios/solicitudes/list');
        $space = $this->createSpace();
        $this->createSpaceRequest($this->createUser(), [
            'espacio_id' => $space->id,
            'fecha' => '2026-06-20',
            'horario' => 'Mañana (8:00 - 12:00)',
            'autorizado' => SolicitudEspacio::APPROVED,
        ]);

        $this->actingAs($user)
            ->put('/espacios/solicitudes/new/create', [
                'espacio' => $space->id,
                'fecha' => '2026-06-20',
                'horario' => 'Mañana (8:00 - 12:00)',
                'detalle' => 'Necesito el consultorio',
            ])
            ->assertOk()
            ->assertViewHas('result', false);

        $this->assertSame(1, SolicitudEspacio::where('espacio_id', $space->id)->count());
    }

    public function test_owner_can_delete_pending_space_request()
    {
        $user = $this->createUserWithAccess('/espacios/solicitudes/list');
        $solicitud = $this->createSpaceRequest($user);

        $this->actingAs($user)
            ->delete("/espacios/solicitudes/{$solicitud->id}/remove")
            ->assertRedirect('/espacios/solicitudes/list');

        $this->assertDatabaseMissing('espacios_pivot', ['id' => $solicitud->id]);
    }

    public function test_user_cannot_delete_another_users_space_request()
    {
        $owner = $this->createUser();
        $other = $this->createUserWithAccess('/espacios/solicitudes/list');
        $solicitud = $this->createSpaceRequest($owner);

        $this->actingAs($other)
            ->delete("/espacios/solicitudes/{$solicitud->id}/remove")
            ->assertForbidden();

        $this->assertDatabaseHas('espacios_pivot', ['id' => $solicitud->id]);
    }

    public function test_user_cannot_delete_approved_space_request()
    {
        $user = $this->createUserWithAccess('/espacios/solicitudes/list');
        $solicitud = $this->createSpaceRequest($user, [
            'autorizado' => SolicitudEspacio::APPROVED,
        ]);

        $this->actingAs($user)
            ->delete("/espacios/solicitudes/{$solicitud->id}/remove")
            ->assertForbidden();

        $this->assertDatabaseHas('espacios_pivot', ['id' => $solicitud->id]);
    }

    public function test_approving_space_request_rejects_only_same_slot_competitors()
    {
        Mail::fake();

        $authorizer = $this->createUserWithAccess('/espacios/solicitudes/autorizar');
        $space = $this->createSpace();
        $approved = $this->createSpaceRequest($this->createUser(), [
            'espacio_id' => $space->id,
            'fecha' => '2026-06-20',
            'horario' => 'Mañana (8:00 - 12:00)',
        ]);
        $sameSlot = $this->createSpaceRequest($this->createUser(), [
            'espacio_id' => $space->id,
            'fecha' => '2026-06-20',
            'horario' => 'Mañana (8:00 - 12:00)',
        ]);
        $otherDate = $this->createSpaceRequest($this->createUser(), [
            'espacio_id' => $space->id,
            'fecha' => '2026-06-21',
            'horario' => 'Mañana (8:00 - 12:00)',
        ]);

        $this->actingAs($authorizer)
            ->put('/espacios/solicitudes/definir', [
                'id' => $approved->id,
                'estado' => SolicitudEspacio::APPROVED,
                'respuesta' => 'Aprobado',
            ])
            ->assertRedirect('/espacios/solicitudes/autorizar');

        $this->assertDatabaseHas('espacios_pivot', [
            'id' => $approved->id,
            'autorizado' => SolicitudEspacio::APPROVED,
        ]);
        $this->assertDatabaseHas('espacios_pivot', [
            'id' => $sameSlot->id,
            'autorizado' => SolicitudEspacio::REJECTED,
        ]);
        $this->assertDatabaseHas('espacios_pivot', [
            'id' => $otherDate->id,
            'autorizado' => SolicitudEspacio::PENDING,
        ]);
        Mail::assertSent(resolucion_espacio::class, 1);
    }

    private function createSpaceRequest(User $user, array $overrides = []): SolicitudEspacio
    {
        return SolicitudEspacio::create(array_merge([
            'user_id' => $user->id,
            'espacio_id' => $this->createSpace()->id,
            'fecha' => '2026-06-20',
            'horario' => 'Mañana (8:00 - 12:00)',
            'detalle' => 'Test request',
            'respuesta' => ' ',
            'autorizado' => SolicitudEspacio::PENDING,
        ], $overrides));
    }

    private function createSpace(): espacio
    {
        $space = new espacio();
        $space->description = uniqid('Consultorio ');
        $space->save();

        return $space;
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
