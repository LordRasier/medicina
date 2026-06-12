<?php

namespace Tests\Unit;

use App\day;
use App\periodo;
use App\request as PeriodRequest;
use App\Services\Periodos\PeriodCalendarService;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PeriodCalendarServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_current_period_from_user_entry_date()
    {
        $user = $this->createUser([
            'ingreso' => now()->year . '-06-01',
            'graduacion' => now()->subYears(5)->toDateString(),
        ]);

        $period = app(PeriodCalendarService::class)->findOrCreateForUser($user);

        $this->assertSame(now()->year . '-06-01', $period->start);
        $this->assertSame(now()->addYear()->year . '-05-31', $period->end);
    }

    public function test_it_calculates_available_days_for_exclusive_dedication()
    {
        $user = $this->createUser([
            'dedication' => 1,
            'ingreso' => now()->subYears(2)->toDateString(),
            'graduacion' => now()->subYears(5)->toDateString(),
        ]);

        $days = app(PeriodCalendarService::class)->calculateAvailableDays($user);

        $this->assertSame(18, $days);
    }

    public function test_it_marks_approved_annual_days_for_users_with_same_specialty()
    {
        $year = now()->year;
        $viewer = $this->createUser(['specialty' => 1]);
        $sameSpecialtyUser = $this->createUser(['specialty' => 1]);
        $otherSpecialtyUser = $this->createUser(['specialty' => 2]);
        $sameSpecialtyDay = $year . '-04-10';
        $otherSpecialtyDay = $year . '-05-12';

        $this->createApprovedRequestDay($sameSpecialtyUser, $sameSpecialtyDay);
        $this->createApprovedRequestDay($otherSpecialtyUser, $otherSpecialtyDay);

        $calendar = app(PeriodCalendarService::class)->buildAnnualSpecialtyCalendarData($viewer, $year);

        $this->assertArrayHasKey($sameSpecialtyDay, $calendar['marcar']);
        $this->assertArrayNotHasKey($otherSpecialtyDay, $calendar['marcar']);
    }

    private function createUser(array $overrides = []): User
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

        foreach ($overrides as $key => $value) {
            $user->{$key} = $value;
        }

        $user->save();

        return $user;
    }

    private function createApprovedRequestDay(User $user, string $date): void
    {
        $period = new periodo();
        $period->user_id = $user->id;
        $period->start = now()->year . '-01-01';
        $period->end = now()->year . '-12-31';
        $period->save();

        $request = new PeriodRequest();
        $request->user_id = $user->id;
        $request->licence_id = 1;
        $request->periodo_id = $period->id;
        $request->description = 'Approved test request';
        $request->autorizacion2 = 2;
        $request->autorizacion3 = 2;
        $request->save();

        $day = new day();
        $day->request_id = $request->id;
        $day->date = $date;
        $day->save();
    }
}
