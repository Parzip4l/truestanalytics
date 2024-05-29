@php
    // Mendapatkan segmen-segmen dari URL saat ini
    $segments = request()->segments();
@endphp
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
        @foreach($segments as $key => $segment)
            @if($loop->last)
                <li class="breadcrumb-item active" aria-current="page">{{ $segment }}</li>
            @else
                <li class="breadcrumb-item"><a href="{{ implode('/', array_slice($segments, 0, $key + 1)) }}">{{ $segment }}</a></li>
            @endif
        @endforeach
    </ol>
</nav>