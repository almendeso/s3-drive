@extends('layouts.base')

@section('content')

@include('css.login')

<body id="login">

    <div class="login-container">

        <div class="login-title">
            📁 {{ config('app.name') }}
            <span>Login</span>
        </div>

        @if($errors->any())
        <div class="error">
            Login inválido
        </div>
        @endif

        <form method="POST" action="/login">
            @csrf

            <div class="input-group">
                <label>Usuário</label>
                <input type="text" name="name" required>
            </div>

            <div class="input-group">
                <label>Senha</label>
                <input type="password" name="password" required>
            </div>

            <div class="form-group remember">
                <label>
                    <input type="checkbox" name="remember">
                    Lembre-me
                </label>
            </div>

            <button class="login-btn">
                Entrar
            </button>

        </form>

    </div>
</body>

@endsection