@php
    $available = (bool) ($available ?? false);
    $availableText = $availableText ?? 'Disponible';
    $missingText = $missingText ?? 'Pendiente';
@endphp

<span class="status-badge @if($available) status-badge-success @else status-badge-muted @endif">
    <i class="far @if($available) fa-check @else fa-clock @endif"></i>
    {{ $available ? $availableText : $missingText }}
</span>
