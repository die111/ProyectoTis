@extends('layouts.guest')
@section('content')
    <main id="register" class="login-section">
        <div class="login-promo">
            <h1 class="promo-title">Únete a Oh! SanSi</h1>
            <div class="promo-image">
                <img src="{{ asset('images/logo_animado.gif') }}" alt="Logo Oh! SanSi">
            </div>
        </div>
        <div class="login-panel">
            <div class="login-content">
                <div class="welcome-header">
                    <h2>Registro</h2>
                    <p>Regístrate como Estudiante</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="login-form">
                    @csrf

                    <div class="form-group">
                        <label for="name">Nombre</label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" required class="@error('name') error @enderror" placeholder="Nombre completo">
                        @error('name') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="last_name_father">Apellido Paterno</label>
                            <input id="last_name_father" name="last_name_father" type="text" value="{{ old('last_name_father') }}" class="@error('last_name_father') error @enderror" placeholder="Apellido paterno">
                            @error('last_name_father') <span class="error-message">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label for="last_name_mother">Apellido Materno</label>
                            <input id="last_name_mother" name="last_name_mother" type="text" value="{{ old('last_name_mother') }}" class="@error('last_name_mother') error @enderror" placeholder="Apellido materno">
                            @error('last_name_mother') <span class="error-message">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Correo electrónico</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required class="@error('email') error @enderror" placeholder="Correo electrónico">
                            @error('email') <span class="error-message">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label for="user_code">Código de usuario (opcional)</label>
                            <input id="user_code" name="user_code" type="text" value="{{ old('user_code') }}" class="@error('user_code') error @enderror" placeholder="Dejar vacío para generar automáticamente">
                            @error('user_code') <span class="error-message">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="ci">Carnet de Identidad</label>
                        <input id="ci" name="ci" type="text" value="{{ old('ci') }}" required class="@error('ci') error @enderror" placeholder="Ingrese su CI">
                        @error('ci') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="password">Contraseña</label>
                            <div class="password-wrapper">
                                <input id="password" name="password" type="password" required class="@error('password') error @enderror" placeholder="Contraseña">
                                <button type="button" id="togglePasswordRegister" class="password-toggle-icon" data-target="password" aria-label="Mostrar contraseña">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password') <span class="error-message">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Confirmar contraseña</label>
                            <div class="password-wrapper">
                                <input id="password_confirmation" name="password_confirmation" type="password" required placeholder="Confirmar contraseña">
                                <button type="button" id="togglePasswordConfirmationRegister" class="password-toggle-icon" data-target="password_confirmation" aria-label="Mostrar contraseña">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Registrarse</button>

                    <div class="separator">o</div>
                    <a href="{{ route('login') }}" class="btn btn-secondary">Ir a Iniciar Sesión</a>
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
