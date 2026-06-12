<?php

namespace Tests\Feature;

use App\day;
use App\licence;
use App\Mail\dispensa_3;
use App\Mail\nueva_dispensa;
use App\periodo;
use App\request as DispensaRequest;
use App\sub_menu;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class DispensaAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_own_dispensa_detail()
    {
        $owner = $this->createUserWithAccess('/misDispensas');
        $solicitud = $this->createDispensaRequest($owner);

        $this->actingAs($owner)
            ->get("/request/detalle/{$solicitud->id}")
            ->assertOk()
            ->assertSee('Respuestas')
            ->assertSee('Dias solicitados');
    }

    public function test_user_cannot_view_another_users_dispensa_detail()
    {
        $owner = $this->createUser();
        $other = $this->createUserWithAccess('/misDispensas');
        $solicitud = $this->createDispensaRequest($owner);

        $this->actingAs($other)
            ->get("/request/detalle/{$solicitud->id}")
            ->assertForbidden();
    }

    public function test_user_can_view_create_dispensa_form()
    {
        $owner = $this->createUserWithAccess('/misDispensas');
        $this->createLicence();

        $this->actingAs($owner)
            ->get('/misDispensas/solicitud')
            ->assertOk()
            ->assertSee('Nueva solicitud de dispensa');
    }

    public function test_user_can_view_own_period_dashboard()
    {
        $owner = $this->createUserWithAccess('/misDispensas');

        $this->actingAs($owner)
            ->get('/misDispensas')
            ->assertOk()
            ->assertSee('Mis dispensas')
            ->assertSee('Calendario del periodo');
    }

    public function test_user_can_view_own_dispensa_requests_list()
    {
        $owner = $this->createUserWithAccess('/misDispensas');
        $this->createDispensaRequest($owner);

        $this->actingAs($owner)
            ->get('/misDispensas/solicitudes')
            ->assertOk()
            ->assertSee('Solicitudes registradas')
            ->assertSee('Pendiente');
    }

    public function test_second_level_authorizer_can_view_pending_requests()
    {
        $authorizer = $this->createUserWithAccess('/autorizaciones/2');
        $this->createDispensaRequest($this->createUser());

        $this->actingAs($authorizer)
            ->get('/autorizaciones/2')
            ->assertOk()
            ->assertSee('Autorizaciones nivel 2')
            ->assertSee('Bandeja de solicitudes');
    }

    public function test_third_level_authorizer_can_view_pending_requests()
    {
        $authorizer = $this->createUserWithAccess('/autorizaciones/3');
        $this->createDispensaRequest($this->createUser(), [
            'autorizacion2' => DispensaRequest::APPROVED,
        ]);

        $this->actingAs($authorizer)
            ->get('/autorizaciones/3')
            ->assertOk()
            ->assertSee('Autorizaciones nivel 3')
            ->assertSee('Bandeja de resolucion final');
    }

    public function test_second_level_authorizer_can_view_resolution_form()
    {
        $authorizer = $this->createUserWithAccess('/autorizaciones/2');
        $solicitud = $this->createDispensaRequest($this->createUser());

        $this->actingAs($authorizer)
            ->get("/autorizaciones/2/definir/{$solicitud->id}")
            ->assertOk()
            ->assertSee('Resolucion nivel 2')
            ->assertSee('Dias solicitados');
    }

    public function test_third_level_authorizer_can_view_resolution_form()
    {
        $authorizer = $this->createUserWithAccess('/autorizaciones/3');
        $solicitud = $this->createDispensaRequest($this->createUser(), [
            'autorizacion2' => DispensaRequest::APPROVED,
        ]);

        $this->actingAs($authorizer)
            ->get("/autorizaciones/3/definir/{$solicitud->id}")
            ->assertOk()
            ->assertSee('Resolucion final')
            ->assertSee('Dias solicitados');
    }

    public function test_authorizer_can_view_dispensa_history()
    {
        $authorizer = $this->createUserWithAccess('/dispensas/historial');
        $this->createDispensaRequest($this->createUser(), [
            'autorizacion2' => DispensaRequest::APPROVED,
            'autorizacion3' => DispensaRequest::APPROVED,
        ]);

        $this->actingAs($authorizer)
            ->get('/dispensas/historial')
            ->assertOk()
            ->assertSee('Historial de dispensas')
            ->assertSee('Solicitudes resueltas');
    }

    public function test_authorizer_can_view_dispensa_history_detail()
    {
        $authorizer = $this->createUserWithAccess('/dispensas/historial');
        $solicitud = $this->createDispensaRequest($this->createUser(), [
            'autorizacion2' => DispensaRequest::APPROVED,
            'autorizacion3' => DispensaRequest::REJECTED,
        ]);

        $this->actingAs($authorizer)
            ->get("/historial/detail/{$solicitud->id}")
            ->assertOk()
            ->assertSee('Detalle historico')
            ->assertSee('Respuestas');
    }

    public function test_user_can_create_dispensa_request_for_own_period()
    {
        Mail::fake();

        $owner = $this->createUserWithAccess('/misDispensas');
        $this->createUserWithAccess('/autorizaciones/2');
        $period = $this->createPeriod($owner);
        $licence = $this->createLicence();

        $this->actingAs($owner)
            ->put('/misDispensas/create/store', [
                'licencia' => $licence->id,
                'dia' => ['2026-06-20'],
                'description' => 'Solicitud de dispensa test',
                'periodo' => $period->id,
            ])
            ->assertRedirect('/misDispensas/solicitudes');

        $this->assertDatabaseHas('requests', [
            'user_id' => $owner->id,
            'licence_id' => $licence->id,
            'periodo_id' => $period->id,
            'description' => 'Solicitud de dispensa test',
        ]);
        $this->assertDatabaseHas('days', [
            'date' => '2026-06-20',
        ]);
        Mail::assertSent(nueva_dispensa::class, 1);
    }

    public function test_user_cannot_create_dispensa_request_for_another_users_period()
    {
        Mail::fake();

        $owner = $this->createUser();
        $other = $this->createUserWithAccess('/misDispensas');
        $period = $this->createPeriod($owner);
        $licence = $this->createLicence();

        $this->actingAs($other)
            ->put('/misDispensas/create/store', [
                'licencia' => $licence->id,
                'dia' => ['2026-06-20'],
                'description' => 'Solicitud invalida',
                'periodo' => $period->id,
            ])
            ->assertForbidden();

        $this->assertDatabaseMissing('requests', [
            'user_id' => $other->id,
            'periodo_id' => $period->id,
        ]);
        Mail::assertNothingSent();
    }

    public function test_second_level_approval_notifies_third_level_authorizers()
    {
        Mail::fake();

        $authorizer = $this->createUserWithAccess('/autorizaciones/2');
        $this->createUserWithAccess('/autorizaciones/3');
        $solicitud = $this->createDispensaRequest($this->createUser());

        $this->actingAs($authorizer)
            ->put("/autorizaciones/2/definir/{$solicitud->id}/update", [
                'id' => $solicitud->id,
                'estado' => DispensaRequest::APPROVED,
                'respuesta' => 'Aprobada en nivel 2',
            ])
            ->assertOk();

        $this->assertSame(DispensaRequest::APPROVED, (int) $solicitud->fresh()->autorizacion2);
        Mail::assertSent(nueva_dispensa::class, 1);
    }

    public function test_third_level_resolution_notifies_request_owner()
    {
        Mail::fake();

        $authorizer = $this->createUserWithAccess('/autorizaciones/3');
        $owner = $this->createUser();
        $solicitud = $this->createDispensaRequest($owner, [
            'autorizacion2' => DispensaRequest::APPROVED,
        ]);

        $this->actingAs($authorizer)
            ->put("/autorizaciones/3/definir/{$solicitud->id}/update", [
                'id' => $solicitud->id,
                'estado' => DispensaRequest::REJECTED,
                'respuesta' => 'Rechazada en nivel 3',
            ])
            ->assertOk();

        $this->assertSame(DispensaRequest::REJECTED, (int) $solicitud->fresh()->autorizacion3);
        Mail::assertSent(dispensa_3::class, 1);
    }

    private function createDispensaRequest(User $user, array $overrides = []): DispensaRequest
    {
        $licence = $this->createLicence();

        $solicitud = new DispensaRequest();
        $solicitud->user_id = $user->id;
        $solicitud->licence_id = $licence->id;
        $solicitud->periodo_id = 1;
        $solicitud->description = 'Solicitud test';

        foreach ($overrides as $key => $value) {
            $solicitud->{$key} = $value;
        }

        $solicitud->save();

        $day = new day();
        $day->request_id = $solicitud->id;
        $day->date = '2026-06-20';
        $day->save();

        return $solicitud;
    }

    private function createLicence(): licence
    {
        $licence = new licence();
        $licence->description = 'Licencia test';
        $licence->alter = 1;
        $licence->habilitado = 1;
        $licence->save();

        return $licence;
    }

    private function createPeriod(User $user): periodo
    {
        $period = new periodo();
        $period->user_id = $user->id;
        $period->start = '2026-01-01';
        $period->end = '2026-12-31';
        $period->save();

        return $period;
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
