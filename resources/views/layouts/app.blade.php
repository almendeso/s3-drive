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

        <button onclick="invalidateFolder('{{ $path }}',this)"
            style="background:none;border:none;cursor:pointer;font-size:16px"
            title="Invalidar cache da pasta">
            🔄
        </button>
    </div>

    <div class="header-right">

        <span class="user-name">
            👤 {{ auth()->user()->name ?? 'User' }}
        </span>

        <form method="POST" action="{{ route('logout') }}" style="display:inline">
            @csrf
            <button type="submit"
                style="background:none;border:none;cursor:pointer;font-size:14px"
                title="Logout">
                🚪 Logout
            </button>
        </form>

    </div>
</header>