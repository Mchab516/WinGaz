@php
$user = auth()->user();
$parts = explode(' ', $user->name ?? '??');
$initials = strtoupper(
\Illuminate\Support\Str::substr($parts[0] ?? '', 0, 1) .
\Illuminate\Support\Str::substr($parts[1] ?? '', 0, 1)
);
@endphp

<div style="background-color: #8bc53f !important;"
    class="text-white rounded-full w-10 h-10 flex items-center justify-center text-sm font-bold md:w-11 md:h-11 md:text-base">
    {{ $initials }}
</div>