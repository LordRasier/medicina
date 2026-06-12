<?php

namespace Tests\Feature;

use App\honorario as Honorario;
use App\request as DispensaRequest;
use App\sub_menu;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_sidebar_menu_icons_are_updated()
    {
        $this->assertSame('fa fa-cogs', DB::table('menus')->where('id', 1)->value('icon'));
        $this->assertSame('fa fa-building', DB::table('menus')->where('id', 3)->value('icon'));
        $this->assertSame('fa fa-briefcase', DB::table('menus')->where('id', 5)->value('icon'));
        $this->assertSame('fa fa-calendar-check', DB::table('menus')->where('id', 6)->value('icon'));
    }

    public function test_dashboard_shows_blocks_for_user_permissions()
    {
        $user = $this->createUserWithAccess(['/misHonorarios', '/misDispensas']);
        $this->createHonorario($user);
        $this->createDispensaRequest($user);

        $this->actingAs($user)
            ->get('/home')
            ->assertOk()
            ->assertSee('Panel operativo')
            ->assertSee('Mis honorarios')
            ->assertSee('Mis dispensas')
            ->assertSee('Honorarios sin factura');
    }

    public function test_dashboard_shows_authorization_blocks_for_authorizer()
    {
        $authorizer = $this->createUserWithAccess(['/autorizaciones/2', '/autorizaciones/3']);
        $this->createDispensaRequest($this->createUser(), [
            'autorizacion2' => DispensaRequest::APPROVED,
        ]);

        $this->actingAs($authorizer)
            ->get('/home')
            ->assertOk()
            ->assertSee('Pendientes nivel 2')
            ->assertSee('Pendientes nivel 3')
            ->assertSee('Autorizar nivel 2');
    }

    private function createHonorario(User $user): Honorario
    {
        $honorario = new Honorario();
        $honorario->user_id = $user->id;
        $honorario->fecha = '2026-06-20';
        $honorario->file = 'app/honorarios/test.pdf';
        $honorario->save();

        return $honorario;
    }

    private function createDispensaRequest(User $user, array $overrides = []): DispensaRequest
    {
        $solicitud = new DispensaRequest();
        $solicitud->user_id = $user->id;
        $solicitud->licence_id = 1;
        $solicitud->periodo_id = 1;
        $solicitud->description = 'Solicitud dashboard';

        foreach ($overrides as $key => $value) {
            $solicitud->{$key} = $value;
        }

        $solicitud->save();

        return $solicitud;
    }

    private function createUserWithAccess(array $urls): User
    {
        $user = $this->createUser();

        foreach ($urls as $url) {
            $subMenu = sub_menu::where('url', $url)->firstOrFail();
            $user->access()->attach($subMenu->id);
        }

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
