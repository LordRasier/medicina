<?php

namespace App\Services\Periodos;

use App\alter;
use App\day;
use App\forbid;
use App\periodo;
use App\User;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class PeriodCalendarService
{
    private const MONTH_LABELS = [
        '01' => 'Ene',
        '02' => 'Feb',
        '03' => 'Mar',
        '04' => 'Abr',
        '05' => 'May',
        '06' => 'Jun',
        '07' => 'Jul',
        '08' => 'Ago',
        '09' => 'Sep',
        '10' => 'Oct',
        '11' => 'Nov',
        '12' => 'Dic',
    ];

    /**
     * Returns the user's annual period for the current, previous, or next cycle.
     */
    public function findOrCreateForUser(User $user, int $yearOffset = 0): periodo
    {
        $anchor = $this->periodAnchorDate($user, $yearOffset);

        $period = $user->periodos()
            ->where('start', '<=', $anchor)
            ->where('end', '>=', $anchor)
            ->first();

        if ($period != null) {
            return $period;
        }

        $period = new periodo();
        $period->user_id = $user->id;
        $period->start = $anchor->toDateString();
        $period->end = $anchor->copy()->addYear()->subDays(1)->toDateString();
        $period->save();

        return $period;
    }

    /**
     * Builds the calendar data consumed by the legacy Blade views.
     */
    public function buildCalendarData(User $user, periodo $period, bool $countOnlyDeductibleLicences = true): array
    {
        $calendarBounds = $this->calendarBounds($period);
        $blockedDays = $this->blockedDaysForPeriod($period, $calendarBounds['start'], $calendarBounds['end']);
        $approvedRequests = $period->dispensas()
            ->where('autorizacion2', 2)
            ->where('autorizacion3', 2)
            ->get();

        $period->requests = $approvedRequests;

        return [
            'periodo' => $period,
            'almanaque' => $this->monthList($calendarBounds['start'], $calendarBounds['end']),
            'domingos' => $blockedDays,
            'disponibles' => $this->calculateAvailableDays($user),
            'usados' => $this->usedDays($approvedRequests, $countOnlyDeductibleLicences),
            'especiales' => $this->extraDays($period),
            'marcar' => $this->markedDays($approvedRequests),
        ];
    }

    /**
     * Builds the annual specialty overview used to compare approved days by peers.
     */
    public function buildAnnualSpecialtyCalendarData(User $user, ?int $year = null): array
    {
        $year = $year ?? Carbon::now()->year;
        $start = new DateTime($year . '-01-01');
        $end = new DateTime($year . '-12-31');
        $markedDays = [];
        $periods = new Collection();

        foreach (User::where('specialty', $user->specialty)->get() as $professional) {
            $professionalPeriods = $this->periodsOverlappingCalendarYear($professional, $year);

            if ($professionalPeriods->isEmpty()) {
                $professionalPeriods = new Collection([$this->findOrCreateForUser($professional)]);
            }

            $periods = $periods->merge($professionalPeriods);

            foreach ($professionalPeriods as $period) {
                $approvedRequests = $period->dispensas()
                    ->where('autorizacion2', 2)
                    ->where('autorizacion3', 2)
                    ->get();

                $markedDays = array_merge($markedDays, $this->markedDaysForCalendarYear($approvedRequests, $year));
            }
        }

        return [
            'periodo' => $periods,
            'almanaque' => $this->monthList($start, $end),
            'domingos' => $this->blockedDaysForCalendarYear($year),
            'disponibles' => $this->calculateAvailableDays($user),
            'marcar' => $markedDays,
            'nextno' => true,
            'prevno' => true,
            'use' => $user,
        ];
    }

    /**
     * Calculates available dispensa days using the existing seniority rules.
     */
    public function calculateAvailableDays(User $user): int
    {
        $dedication = $user->dedication;
        $seniority = date_diff(new DateTime($user->ingreso), new DateTime(now()))->y * 2;

        $graduation = date_diff(new DateTime($user->graduacion), new DateTime(now()));
        $graduationYears = ($graduation->y > 15) ? 15 : $graduation->y;

        $score = $graduationYears + $seniority;

        switch ($dedication) {
            case 1:
                if ($score < 20) {
                    return 18;
                }

                return ($score < 30) ? 23 : 27;

            case 2:
                if ($score < 20) {
                    return 14;
                }

                return ($score < 30) ? 19 : 23;
        }

        return 0;
    }

    private function periodAnchorDate(User $user, int $yearOffset): Carbon
    {
        $entryDate = Carbon::parse($user->ingreso);

        return Carbon::create(Carbon::now()->year, $entryDate->month, $entryDate->day)
            ->addYears($yearOffset);
    }

    private function calendarBounds(periodo $period): array
    {
        return [
            'start' => (new DateTime($period->start))->modify('first day of this month'),
            'end' => (new DateTime($period->end))->modify('last day of this month'),
        ];
    }

    private function periodsOverlappingCalendarYear(User $user, int $year): Collection
    {
        $start = $year . '-01-01';
        $end = $year . '-12-31';

        return $user->periodos()
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('start', [$start, $end])
                    ->orWhereBetween('end', [$start, $end])
                    ->orWhere(function ($query) use ($start, $end) {
                        $query->where('start', '<=', $start)
                            ->where('end', '>=', $end);
                    });
            })
            ->get();
    }

    private function blockedDaysForPeriod(periodo $period, DateTime $start, DateTime $end): array
    {
        $blockedDays = [];
        $oneDay = DateInterval::createFromDateString('1 day');

        foreach (new DatePeriod(clone $start, $oneDay, new DateTime($period->start)) as $day) {
            $blockedDays[$day->format('Y-m-d')] = $day->format('Y-m-d');
            $blockedDays = $this->appendForbiddenDays($blockedDays, $day->format('Y'));
        }

        foreach (new DatePeriod(new DateTime($period->end), $oneDay, clone $end) as $day) {
            $blockedDays[$day->format('Y-m-d')] = $day->format('Y-m-d');
            $blockedDays = $this->appendForbiddenDays($blockedDays, $day->format('Y'));
        }

        $cursor = clone $start;
        while ($cursor <= $end) {
            if ($cursor->format('w') == 0 || $cursor->format('w') == 6) {
                $blockedDays[$cursor->format('Y-m-d')] = $cursor->format('Y-m-d');
            }

            $blockedDays = $this->appendInvalidMonthDays($blockedDays, $cursor);
            $cursor->modify('+1 day');
        }

        return $blockedDays;
    }

    private function blockedDaysForCalendarYear(int $year): array
    {
        $blockedDays = $this->appendForbiddenDays([], (string) $year);
        $cursor = new DateTime($year . '-01-01');
        $end = new DateTime($year . '-12-31');

        while ($cursor <= $end) {
            if ($cursor->format('w') == 0 || $cursor->format('w') == 6) {
                $blockedDays[$cursor->format('Y-m-d')] = $cursor->format('Y-m-d');
            }

            $blockedDays = $this->appendInvalidMonthDays($blockedDays, $cursor);
            $cursor->modify('+1 day');
        }

        return $blockedDays;
    }

    private function appendForbiddenDays(array $blockedDays, string $year): array
    {
        foreach (forbid::whereYear('day', $year)->get() as $forbiddenDay) {
            $blockedDays[$forbiddenDay->day] = $forbiddenDay->day;
        }

        return $blockedDays;
    }

    private function appendInvalidMonthDays(array $blockedDays, DateTime $day): array
    {
        $yearMonth = $day->format('Y-m');
        $year = (int) $day->format('Y');
        $month = (int) $day->format('m');

        foreach ([29, 30, 31] as $monthDay) {
            if (! checkdate($month, $monthDay, $year)) {
                $date = sprintf('%s-%02d', $yearMonth, $monthDay);
                $blockedDays[$date] = $date;
            }
        }

        return $blockedDays;
    }

    private function monthList(DateTime $start, DateTime $end): array
    {
        $months = [];
        $oneMonth = DateInterval::createFromDateString('1 month');

        foreach (new DatePeriod(clone $start, $oneMonth, clone $end) as $month) {
            $months[] = [
                $month->format('Y-m'),
                $month->format('Y') . '-' . self::MONTH_LABELS[$month->format('m')],
            ];
        }

        return $months;
    }

    private function usedDays($requests, bool $countOnlyDeductibleLicences): int
    {
        $usedDays = 0;

        foreach ($requests as $request) {
            $licence = DB::table('licences')->where('id', $request->licence_id)->first();
            $request->temporal = $licence;

            if (! $countOnlyDeductibleLicences || ($licence != null && $licence->alter == 1)) {
                $usedDays += day::where('request_id', $request->id)->count();
            }
        }

        return $usedDays;
    }

    private function markedDays($requests): array
    {
        $markedDays = [];

        foreach ($requests as $request) {
            foreach (day::where('request_id', $request->id)->get() as $day) {
                $markedDays[$day->date] = $day->date;
            }
        }

        return $markedDays;
    }

    private function markedDaysForCalendarYear($requests, int $year): array
    {
        $markedDays = [];

        foreach ($requests as $request) {
            foreach (day::where('request_id', $request->id)->whereYear('date', $year)->get() as $day) {
                $markedDays[$day->date] = $day->date;
            }
        }

        return $markedDays;
    }

    private function extraDays(periodo $period): int
    {
        $extraDays = 0;

        foreach (alter::where('periodo_id', $period->id)->get() as $extra) {
            $extraDays += $extra->days;
        }

        return $extraDays;
    }
}
