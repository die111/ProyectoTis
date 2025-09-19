@extends('layouts.guest')
@section('content')
<main id="login" class="login-section">
    <div class="login-promo">
        <h1 class="promo-title">Oh! SanSi</h1>
        <div class="promo-image">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Oh! SanSi">
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
                        <button type="button" id="togglePassword" class="password-toggle-icon">
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const icon = togglePassword.querySelector('i');

        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);

            if (type === 'text') {
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
</script>
@endsection
<style>
    :root {
        --color-primary: #091c47;
        --color-white: #ffffff;
        --color-black: #000000;
        --color-text-dark: #1e1e1e;
        --color-gray-light: #f5f4f4;
        --color-gray-medium: #d9d8d8;
        --color-gray-dark: #2c2c2c;
        --color-error: #dc2626;
        --color-success: #16a34a;
        --font-poppins: 'Poppins', sans-serif;
        --font-inter: 'Inter', sans-serif;
        --font-quicksand: 'Quicksand', sans-serif;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: var(--font-inter);
        background-color: #f9f9f9;
        color: var(--color-text-dark);
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    .login-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        min-height: 100vh;
        flex: 1;
    }

    .login-promo {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 40px;
        gap: 40px;
        background: linear-gradient(135deg, #f5f7fa 0%, #ffffff 100%);
    }

    .promo-title {
        font-family: var(--font-poppins);
        font-weight: 700;
        font-size: 75px;
        line-height: 1.3;
        text-align: center;
        margin: 0;
        color: var(--color-primary);
    }

    .promo-image {
        max-width: 100%;
        width: 500px;
        height: 500px;
        /* background-color: var(--color-primary); */
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--color-white);
        font-size: 100px;
        /* border-radius: 15px; */
        /* box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15); */
        overflow: hidden;
    }

    .promo-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .login-panel {
        background-color: var(--color-gray-medium);
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 40px;
    }

    .login-content {
        background-color: var(--color-white);
        border: 1px solid #d9d9d9;
        border-radius: 8px;
        padding: 24px;
        width: 100%;
        max-width: 497px;
        display: flex;
        flex-direction: column;
        gap: 24px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .welcome-header {
        text-align: center;
            padding: 24px;
            background-color: var(--color-gray-medium);
            width: 100%;
            margin: 0; 
            box-sizing: border-box; 
    }

    .welcome-header h2 {
        font-family: var(--font-quicksand);
        font-weight: 700;
        font-size: 48px;
        letter-spacing: 5.76px;
        margin: 0;
        color: var(--color-primary);
    }

    .welcome-header p {
        font-family: var(--font-quicksand);
        font-weight: 500;
        font-size: 24px;
        margin: 20px 0 0;
        color: var(--color-text-dark);
    }

    .login-prompt {
        font-family: var(--font-quicksand);
        font-weight: 500;
        font-size: 20px;
        text-align: center;
        margin: 0;
        color: var(--color-text-dark);
    }

    .login-form {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-group label {
        font-family: var(--font-inter);
        font-size: 16px;
        color: var(--color-text-dark);
        font-weight: 500;
    }

    .form-group input {
        border: 1px solid var(--color-gray-dark);
        border-radius: 8px;
        padding: 12px 16px;
        font-size: 16px;
        height: 40px;
        font-family: var(--font-inter);
    }

    .form-group input:focus {
        outline: none;
        border-color: var(--color-primary);
        box-shadow: 0 0 0 2px rgba(9, 28, 71, 0.1);
    }

    .form-group input.error {
        border-color: var(--color-error);
    }

    #email {
        background-color: var(--color-gray-light);
    }

    .password-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .password-wrapper input {
        background-color: #e3e3e3;
        width: 100%;
    }

    .password-toggle-icon {
        position: absolute;
        right: 16px;
        width: 22px;
        height: 17.5px;
        cursor: pointer;
        color: var(--color-gray-dark);
    }

    .forgot-password {
        font-family: var(--font-inter);
        font-size: 16px;
        color: var(--color-black);
        align-self: flex-start;
        text-decoration: underline;
        transition: color 0.3s ease;
        cursor: pointer;
    }

    .forgot-password:hover {
        color: var(--color-primary);
    }

    .btn {
        border-radius: 8px;
        padding: 12px;
        font-size: 16px;
        text-align: center;
        height: 40px;
        font-family: var(--font-inter);
        font-weight: 500;
        transition: all 0.3s ease;
        cursor: pointer;
        border: none;
    }

    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .btn-primary {
        background-color: var(--color-primary);
        color: var(--color-white);
    }

    .btn-primary:hover:not(:disabled) {
        background-color: #0a1e4a;
        transform: translateY(-1px);
    }

    .separator {
        text-align: center;
        font-family: var(--font-inter);
        font-size: 16px;
        color: var(--color-black);
        position: relative;
    }

    .separator::before,
    .separator::after {
        content: '';
        position: absolute;
        top: 50%;
        width: 45%;
        height: 1px;
        background-color: var(--color-gray-dark);
    }

    .separator::before {
        left: 0;
    }

    .separator::after {
        right: 0;
    }

    .btn-secondary {
        background-color: transparent;
        color: var(--color-primary);
        border: 1px solid var(--color-primary);
        height: 38px;
    }

    .btn-secondary:hover:not(:disabled) {
        background-color: rgba(9, 28, 71, 0.05);
        transform: translateY(-1px);
    }

    .error-message {
        color: var(--color-error);
        font-size: 14px;
        margin-top: 5px;
        font-family: var(--font-inter);
    }

    .success-message {
        color: var(--color-success);
        font-size: 14px;
        margin-top: 5px;
        font-family: var(--font-inter);
    }

    .error-container {
        background-color: #fef2f2;
        border: 1px solid #fecaca;
        color: var(--color-error);
        padding: 12px 16px;
        border-radius: 8px;
        font-size: 14px;
    }

    .error-container ul {
        list-style-type: none;
    }

    .remember-forgot {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .remember-me {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .remember-me input {
        width: 16px;
        height: 16px;
    }

    .remember-me label {
        font-family: var(--font-inter);
        font-size: 14px;
    }

    @media (max-width: 992px) {
        .login-section {
            grid-template-columns: 1fr;
        }

        .login-promo {
            padding: 20px;
        }

        .promo-title {
            font-size: 48px;
        }

        .promo-image {
            width: 200px;
            height: 200px;
        }

        .login-panel {
            padding: 20px;
        }

        .welcome-header h2 {
            font-size: 36px;
            letter-spacing: 3px;
        }

        .welcome-header p {
            font-size: 20px;
        }
    }

    @media (max-width: 640px) {
        .login-promo {
            padding: 15px;
            gap: 20px;
        }

        .promo-title {
            font-size: 36px;
        }

        .promo-image {
            width: 150px;
            height: 150px;
        }

        .login-panel {
            padding: 15px;
        }

        .login-content {
            padding: 20px;
        }

        .welcome-header h2 {
            font-size: 28px;
            letter-spacing: 2px;
        }

        .welcome-header p {
            font-size: 18px;
        }

        .login-prompt {
            font-size: 18px;
        }

        .remember-forgot {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }
    }
</style>