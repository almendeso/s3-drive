@extends('layouts.base')

@section('content')

@include('css.login')

<body id="login">

<div class="login-card">

    <div class="logo">📁</div>

    <div class="login-title">
        {{ config('app.name') }}
        <span>Login</span>
    </div>

    <form method="POST" action="">
        @csrf

        <div class="form-group">
            <label>Usuário</label>

            <input
                id="name"
                type="text"
                name="name"
                class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name') }}"
                required
                autofocus
                autocomplete="username"
            >

            @error('name')
                <div style="color:#dc2626;font-size:13px;margin-top:4px">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group">
            <label>Senha</label>

            <input
                id="password"
                type="password"
                name="password"
                class="form-control @error('password') is-invalid @enderror"
                required
                autocomplete="current-password"
            >

            @error('password')
                <div style="color:#dc2626;font-size:13px;margin-top:4px">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group remember">
            <label>
                <input type="checkbox" name="remember">
                Lembre-me
            </label>
        </div>

        <button type="submit" class="login-btn">
            Login
        </button>

    </form>

</div>

</body>

@endsection