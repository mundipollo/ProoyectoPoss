@props([
    'title',
    'subtitle' => null,
])

@php
    $letters = preg_split('//u', mb_strtoupper($title), -1, PREG_SPLIT_NO_EMPTY);
@endphp

<div class="store-hero-heading">
    <h1 class="hero-title text-white text-center select-none">
        @foreach ($letters as $letter)
            <span>{{ $letter }}</span>
        @endforeach
    </h1>
    @if ($subtitle)
        <p class="hero-subtitle text-center">{{ $subtitle }}</p>
    @endif
</div>
