@php
    $fallback = $fallback ?? '/img/iso.png';
    $profile = $profile ?? null;
    $name = $name ?? 'Usuario';
    $src = $src ?? ($profile ? '/storage/profile/' . ltrim($profile, '/') : $fallback);
    $class = $class ?? 'profile-image rounded-circle';
    $loading = $loading ?? 'lazy';
@endphp

<img
    src="{{ $src }}"
    class="{{ $class }} js-profile-avatar"
    alt="{{ $name }}"
    loading="{{ $loading }}"
    onerror="this.onerror=null;this.src='{{ $fallback }}';this.classList.add('profile-avatar-fallback');"
>
