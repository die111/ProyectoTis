@extends('layouts.guest')
@section('content')
    <main id="login" class="login-section">
        <div class="login-promo">
            <h1 class="promo-title">Oh! SanSi</h1>
            <div class="promo-image">
                <img src="{{ asset('images/logo_animado.gif') }}" alt="Logo Oh! SanSi">
            </div>
        </div>
        <div class="login-panel">
            <div class="login-content">
                <div class="welcome-header">
                    <h2>¡Bienvenido!</h2>
                    <p>¿Listo Para un Nuevo Día?</p>
                </div>
                <p class="login-prompt">Inicia Sesión para continuar</p>

                <form class="login-form" method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group">
                        <label for="email">Correo electrónico</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                            autocomplete="email" autofocus class="@error('email') error @enderror"
                            placeholder="Correo electrónico">
                        @error('email')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <div class="password-wrapper">
                            <input type="password" id="password" name="password" required autocomplete="current-password"
                                class="@error('password') error @enderror" placeholder="Contraseña">
                            <button type="button" id="togglePassword" class="password-toggle-icon" data-target="password" aria-label="Mostrar contraseña">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    @if ($errors->any())
                        <div class="error-container">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="remember-forgot">
                        <div class="remember-me">
                            <input id="remember" name="remember" type="checkbox">
                            <label for="remember">Recordarme</label>
                        </div>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-password">¿Olvidaste tu contraseña?</a>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary">Iniciar Sesión</button>

                    @if (Route::has('register'))
                        <div class="separator">o</div>
                        <a href="{{ route('register') }}" class="btn btn-secondary">Registrarse</a>
                    @endif
                </form>
            </div>
        </div>
    </main>
@endsection
@section('scripts')
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush
