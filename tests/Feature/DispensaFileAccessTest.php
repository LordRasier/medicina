<?php

namespace Tests\Feature;

use App\request as DispensaRequest;
use App\sub_menu;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DispensaFileAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_view_dispensa_file()
    {
        Storage::fake('local');

        $owner = $this->createUser();
        $solicitud = $this->createDispensaRequest($owner);

        $this->actingAs($owner)
            ->get("/checkfile/{$solicitud->id}")
            ->assertOk();
    }

    public function test_user_without_access_cannot_view_another_users_dispensa_file()
    {
        Storage::fake('local');

        $owner = $this->createUser();
        $other = $this->createUser();
        $solicitud = $this->createDispensaRequest($owner);

        $this->actingAs($other)
            ->get("/checkfile/{$solicitud->id}")
            ->assertForbidden();
    }

    public function test_authorizer_can_view_another_users_dispensa_file()
    {
        Storage::fake('local');

        $owner = $this->createUser();
        $authorizer = $this->createUserWithAccess('/autorizaciones/2');
        $solicitud = $this->createDispensaRequest($owner);

        $this->actingAs($authorizer)
            ->get("/checkfile/{$solicitud->id}")
            ->assertOk();
    }

    private function createDispensaRequest(User $user): DispensaRequest
    {
        $path = 'app/dispensas/test.pdf';
        Storage::put($path, 'test file');

        $solicitud = new DispensaRequest();
        $solicitud->user_id = $user->id;
        $solicitud->licence_id = 1;
        $solicitud->periodo_id = 1;
        $solicitud->description = 'Test request';
        $solicitud->file = $path;
        $solicitud->save();

        return $solicitud;
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
