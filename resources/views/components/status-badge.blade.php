@php
    $status = (int) $status;
    $labels = [
        0 => ['text' => 'Rechazada', 'class' => 'status-badge-danger', 'icon' => 'fa-times'],
        1 => ['text' => 'Pendiente', 'class' => 'status-badge-warning', 'icon' => 'fa-clock'],
        2 => ['text' => 'Aprobada', 'class' => 'status-badge-success', 'icon' => 'fa-check'],
    ];
    $statusData = $labels[$status] ?? ['text' => 'Sin estado', 'class' => 'status-badge-muted', 'icon' => 'fa-minus'];
@endphp

<span class="status-badge {{ $statusData['class'] }}">
    <i class="far {{ $statusData['icon'] }}"></i>
    {{ $statusData['text'] }}
</span>
