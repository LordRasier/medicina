<?php

namespace App\Services\Dashboard;

use App\event;
use App\honorario;
use App\request as DispensaRequest;
use App\Services\Periodos\PeriodCalendarService;
use App\SolicitudEspacio;
use App\User;
use Carbon\Carbon;

class DashboardService
{
    private PeriodCalendarService $periodCalendar;

    public function __construct(PeriodCalendarService $periodCalendar)
    {
        $this->periodCalendar = $periodCalendar;
    }

    /**
     * Builds the dashboard blocks that are useful for the user's current menu permissions.
     */
    public function forUser(User $user): array
    {
        $quickActions = $this->quickActions($user);

        return [
            'primaryAction' => $quickActions[0] ?? null,
            'quickActions' => $quickActions,
            'attentionItems' => $this->attentionItems($user),
            'metrics' => $this->metrics($user),
            'sections' => $this->sections($user),
        ];
    }

    private function quickActions(User $user): array
    {
        $actions = [];

        $this->appendAction($actions, $user, ['/misDispensas'], 'Nueva dispensa', '/misDispensas/solicitud', 'fa fa-calendar-plus');
        $this->appendAction($actions, $user, ['/misHonorarios'], 'Mis honorarios', '/misHonorarios', 'fa fa-briefcase');
        $this->appendAction($actions, $user, ['/espacios/solicitudes/list'], 'Reservar espacio', '/espacios/solicitudes/new', 'fa fa-building');
        $this->appendAction($actions, $user, ['/autorizaciones/2'], 'Autorizar nivel 2', '/autorizaciones/2', 'fa fa-clipboard-check');
        $this->appendAction($actions, $user, ['/autorizaciones/3'], 'Autorizar nivel 3', '/autorizaciones/3', 'fa fa-check-square');
        $this->appendAction($actions, $user, ['/honorarios/create'], 'Cargar honorarios', '/honorarios/create', 'fa fa-upload');

        return $actions;
    }

    private function appendAction(array &$actions, User $user, array $permissions, string $label, string $url, string $icon): void
    {
        if ($user->canAccessAnyMenu($permissions)) {
            $actions[] = compact('label', 'url', 'icon');
        }
    }

    private function metrics(User $user): array
    {
        $metrics = [
            [
                'label' => 'Eventos proximos',
                'value' => event::where('user', $user->id)
                    ->whereDate('fecha', '>=', Carbon::today())
                    ->count(),
                'icon' => 'fa fa-calendar-alt',
            ],
        ];

        if ($user->canAccessAnyMenu(['/misDispensas'])) {
            $period = $this->periodCalendar->findOrCreateForUser($user);
            $available = $this->periodCalendar->calculateAvailableDays($user);
            $used = $period->dispensas()
                ->where('autorizacion2', DispensaRequest::APPROVED)
                ->where('autorizacion3', DispensaRequest::APPROVED)
                ->withCount('days')
                ->get()
                ->sum('days_count');
            $extras = $period->extras()->sum('days');

            $metrics[] = [
                'label' => 'Dispensas disponibles',
                'value' => max(0, $available + $extras - $used),
                'icon' => 'fa fa-calendar-check',
            ];
        }

        if ($user->canAccessAnyMenu(['/misHonorarios'])) {
            $metrics[] = [
                'label' => 'Honorarios sin factura',
                'value' => $user->honorarios()->whereNull('factura')->count(),
                'icon' => 'fa fa-briefcase',
            ];
        }

        if ($user->canAccessAnyMenu(['/autorizaciones/2'])) {
            $metrics[] = [
                'label' => 'Pendientes nivel 2',
                'value' => DispensaRequest::waitingSecondAuthorization()->count(),
                'icon' => 'fa fa-clipboard-check',
            ];
        }

        if ($user->canAccessAnyMenu(['/autorizaciones/3'])) {
            $metrics[] = [
                'label' => 'Pendientes nivel 3',
                'value' => DispensaRequest::waitingThirdAuthorization()->count(),
                'icon' => 'fa fa-check-square',
            ];
        }

        if ($user->canAccessAnyMenu(['/espacios/solicitudes/autorizar'])) {
            $metrics[] = [
                'label' => 'Espacios por autorizar',
                'value' => SolicitudEspacio::pending()->count(),
                'icon' => 'fa fa-building',
            ];
        }

        if ($user->canAccessAnyMenu(['/honorarios'])) {
            $metrics[] = [
                'label' => 'Facturas cargadas',
                'value' => honorario::whereNotNull('factura')->count(),
                'icon' => 'fa fa-file-alt',
            ];
        }

        return $metrics;
    }

    private function attentionItems(User $user): array
    {
        $items = [];

        if ($user->canAccessAnyMenu(['/autorizaciones/2'])) {
            $this->appendAttentionItem(
                $items,
                'Autorizaciones nivel 2',
                DispensaRequest::waitingSecondAuthorization()->count(),
                '/autorizaciones/2',
                'fa fa-clipboard-check'
            );
        }

        if ($user->canAccessAnyMenu(['/autorizaciones/3'])) {
            $this->appendAttentionItem(
                $items,
                'Autorizaciones nivel 3',
                DispensaRequest::waitingThirdAuthorization()->count(),
                '/autorizaciones/3',
                'fa fa-check-square'
            );
        }

        if ($user->canAccessAnyMenu(['/misHonorarios'])) {
            $this->appendAttentionItem(
                $items,
                'Honorarios sin factura',
                $user->honorarios()->whereNull('factura')->count(),
                '/misHonorarios',
                'fa fa-briefcase'
            );
        }

        if ($user->canAccessAnyMenu(['/espacios/solicitudes/autorizar'])) {
            $this->appendAttentionItem(
                $items,
                'Reservas pendientes',
                SolicitudEspacio::pending()->count(),
                '/espacios/solicitudes/autorizar',
                'fa fa-building'
            );
        }

        if ($user->canAccessAnyMenu(['/misDispensas'])) {
            $this->appendAttentionItem(
                $items,
                'Mis dispensas pendientes',
                DispensaRequest::where('user_id', $user->id)
                    ->where(function ($query) {
                        $query->where('autorizacion2', DispensaRequest::PENDING)
                            ->orWhere('autorizacion3', DispensaRequest::PENDING);
                    })
                    ->count(),
                '/misDispensas/solicitudes',
                'fa fa-calendar-check'
            );
        }

        return $items;
    }

    private function appendAttentionItem(array &$items, string $label, int $count, string $url, string $icon): void
    {
        $items[] = [
            'label' => $label,
            'value' => $count,
            'url' => $url,
            'icon' => $icon,
            'tone' => $count > 0 ? 'warning' : 'success',
        ];
    }

    private function sections(User $user): array
    {
        $sections = [];

        if ($user->canAccessAnyMenu(['/misDispensas'])) {
            $sections[] = [
                'title' => 'Mis dispensas',
                'description' => 'Ultimas solicitudes cargadas.',
                'url' => '/misDispensas/solicitudes',
                'items' => DispensaRequest::with('licence')
                    ->where('user_id', $user->id)
                    ->orderByDesc('id')
                    ->limit(5)
                    ->get()
                    ->map(fn ($request) => [
                        'label' => '#' . $request->id . ' - ' . optional($request->licence)->description,
                        'meta' => $request->created_at->format('d/m/Y'),
                        'status' => $this->dispensaStatus($request),
                    ])
                    ->all(),
            ];
        }

        if ($user->canAccessAnyMenu(['/misHonorarios'])) {
            $sections[] = [
                'title' => 'Mis honorarios',
                'description' => 'Ultimas liquidaciones recibidas.',
                'url' => '/misHonorarios',
                'items' => $user->honorarios()
                    ->orderByDesc('id')
                    ->limit(5)
                    ->get()
                    ->map(fn ($honorario) => [
                        'label' => '#' . $honorario->id,
                        'meta' => $honorario->fecha,
                        'status' => $honorario->factura ? 'Facturada' : 'Sin factura',
                    ])
                    ->all(),
            ];
        }

        if ($user->canAccessAnyMenu(['/espacios/solicitudes/list'])) {
            $sections[] = [
                'title' => 'Mis reservas de espacio',
                'description' => 'Solicitudes proximas o recientes.',
                'url' => '/espacios/solicitudes/list',
                'items' => $user->solicitudesEspacio()
                    ->with('espacio')
                    ->orderByDesc('id')
                    ->limit(5)
                    ->get()
                    ->map(fn ($solicitud) => [
                        'label' => optional($solicitud->espacio)->description ?? 'Espacio no disponible',
                        'meta' => $solicitud->fecha . ' - ' . $solicitud->horario,
                        'status' => $this->spaceStatus((int) $solicitud->autorizado),
                    ])
                    ->all(),
            ];
        }

        if ($user->canAccessAnyMenu(['/autorizaciones/2'])) {
            $sections[] = $this->authorizationSection('Pendientes nivel 2', '/autorizaciones/2', DispensaRequest::waitingSecondAuthorization());
        }

        if ($user->canAccessAnyMenu(['/autorizaciones/3'])) {
            $sections[] = $this->authorizationSection('Pendientes nivel 3', '/autorizaciones/3', DispensaRequest::waitingThirdAuthorization());
        }

        return $sections;
    }

    private function authorizationSection(string $title, string $url, $query): array
    {
        return [
            'title' => $title,
            'description' => 'Solicitudes que requieren accion.',
            'url' => $url,
            'items' => $query
                ->with(['user', 'licence'])
                ->orderByDesc('id')
                ->limit(5)
                ->get()
                ->map(fn ($request) => [
                    'label' => '#' . $request->id . ' - ' . optional($request->user)->name,
                    'meta' => optional($request->licence)->description ?? 'Sin tipo',
                    'status' => $this->dispensaStatus($request),
                ])
                ->all(),
        ];
    }

    private function dispensaStatus(DispensaRequest $request): string
    {
        if ((int) $request->autorizacion2 === DispensaRequest::REJECTED || (int) $request->autorizacion3 === DispensaRequest::REJECTED) {
            return 'Rechazada';
        }

        if ((int) $request->autorizacion2 === DispensaRequest::APPROVED && (int) $request->autorizacion3 === DispensaRequest::APPROVED) {
            return 'Aprobada';
        }

        return 'Pendiente';
    }

    private function spaceStatus(int $status): string
    {
        return match ($status) {
            SolicitudEspacio::APPROVED => 'Aprobada',
            SolicitudEspacio::REJECTED => 'Rechazada',
            default => 'Pendiente',
        };
    }
}
