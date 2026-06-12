@php
    $breadcrumbs = $breadcrumbs ?? [];
@endphp

<ol class="breadcrumb page-breadcrumb">
    <li class="breadcrumb-item"><a href="/home">Inicio</a></li>
    @foreach($breadcrumbs as $breadcrumb)
        <li class="breadcrumb-item @if($loop->last) active @endif">
            @isset($breadcrumb['url'])
                <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['label'] }}</a>
            @else
                {{ $breadcrumb['label'] }}
            @endisset
        </li>
    @endforeach
</ol>

<div class="ui-page-header">
    <div>
        <h1 class="page-title mb-1">{{ $title }}</h1>
        @isset($subtitle)
            <div class="text-muted">{{ $subtitle }}</div>
        @endisset
    </div>

    @isset($slot)
        <div class="ui-page-actions">
            {{ $slot }}
        </div>
    @endisset
</div>
