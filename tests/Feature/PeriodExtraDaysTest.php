<?php

namespace Tests\Feature;

use App\Mail\extras as ExtrasMail;
use App\periodo;
use App\sub_menu;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PeriodExtraDaysTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_assign_extra_days_to_period()
    {
        Mail::fake();

        $admin = $this->createUserWithAccess('/editperiod');
        $professional = $this->createUser();
        $period = $this->createPeriod($professional);

        $this->actingAs($admin)
            ->put("/editperiod/{$professional->id}/update", [
                'id' => $period->id,
                'observacion' => 'Dias extra por guardia',
                'cantidad' => 3,
            ])
            ->assertOk()
            ->assertViewHas('resp', true);

        $this->assertDatabaseHas('alters', [
            'periodo_id' => $period->id,
            'description' => 'Dias extra por guardia',
            'days' => 3,
        ]);
        Mail::assertSent(ExtrasMail::class, 1);
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
