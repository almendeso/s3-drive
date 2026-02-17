<header class="app-header">
    <div class="header-left">
        <span class="logo">📁</span>
        <span class="app-name">{{ config('app.name') }}</span>

        <nav class="breadcrumb">
            <span>/</span>
            @php
                $segments = $path ? explode('/', $path) : [];
                $acc = '';
            @endphp

            @foreach ($segments as $segment)
                @php $acc = trim($acc.'/'.$segment,'/'); @endphp
                <a href="{{ route('files.index',['path'=>$acc]) }}">
                    {{ $segment }}
                </a>
                <span>/</span>
            @endforeach
        </nav>
    </div>

    <div class="header-right">
        <form method="get" class="search-box">
            <input type="hidden" name="path" value="{{ $path }}">
        </form>
    </div>
</header>
