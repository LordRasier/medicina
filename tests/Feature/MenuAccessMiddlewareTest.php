<?php

namespace Tests\Feature;

use App\sub_menu;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MenuAccessMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_access_routes_for_assigned_sub_menu()
    {
        $user = $this->createUserWithAccess('/users');

        $this->actingAs($user)
            ->get('/users')
            ->assertOk();
    }

    public function test_user_cannot_access_routes_without_assigned_sub_menu()
    {
        $user = $this->createUserWithAccess('/users');

        $this->actingAs($user)
            ->get('/espacios/list')
            ->assertForbidden();
    }

    private function createUserWithAccess(string $url): User
    {
        $user = new User();
        $user->code = 'TST';
        $user->name = 'Test User';
        $user->doc = uniqid('doc');
        $user->email = uniqid('test') . '@example.com';
        $user->password = Hash::make('password');
        $user->specialty = 1;
        $user->type = 1;
        $user->dedication = 1;
        $user->save();

        $subMenu = sub_menu::where('url', $url)->firstOrFail();
        $user->access()->attach($subMenu->id);

        return $user;
    }
}
