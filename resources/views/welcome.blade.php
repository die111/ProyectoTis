<style>
    :root {
        --color-primary: #091c47;
        --color-light-bg: #f5f4f4;
        --color-white: #ffffff;
        --color-black: #000000;
        --color-text-dark: #2d2d2d;
        --color-text-light: #fff8f8;
        --font-poppins: 'Poppins', sans-serif;
        --font-mulish: 'Mulish', sans-serif;
    }

    body {
        font-family: var(--font-poppins);
        background-color: var(--color-white);
        color: var(--color-black);
    }

    .section-container {
        padding: 60px 50px;
    }

    @media (max-width: 992px) {
        .section-container {
            padding: 40px 30px;
        }
    }

    @media (max-width: 768px) {
        .section-container {
            padding: 30px 20px;
        }
    }

    /* Hero Section */
    .hero-content {
        display: flex;
        align-items: center;
        gap: 27px;
    }

    .hero-image-container {
        flex: 0 0 379px;
    }

    .hero-image-container img {
        width: 100%;
        height: auto;
        display: block;
        border-radius: 10px;
    }

    .hero-text-container {
        flex: 1;
    }

    .hero-text-container p {
        color: var(--color-text-dark);
        font-family: var(--font-mulish);
        font-weight: 400;
        font-size: 21px;
        line-height: 1.5;
        margin: 0;
    }

    @media (max-width: 992px) {
        .hero-content {
            flex-direction: column;
            text-align: center;
        }

        .hero-image-container {
            flex: 0 0 auto;
            max-width: 500px;
        }
    }

    /* Olympiads Section */
    .olympiads-section {
        text-align: center;
        background-color: var(--color-white);
    }

    .olympiads-title {
        color: var(--color-black);
        font-family: var(--font-poppins);
        font-weight: 700;
        font-size: 20px;
        line-height: 26px;
        margin: 0 0 37px 0;
    }

    .olympiads-description {
        max-width: 1392px;
        margin: 0 auto;
        color: var(--color-text-dark);
        font-family: var(--font-mulish);
        font-weight: 400;
        font-size: 21px;
        line-height: 1.5;
    }

    /* Subjects Section */
    .subjects-section {
        background-color: var(--color-light-bg);
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .subjects-title-wrapper {
        text-align: center;
        margin-bottom: 40px;
    }

    .subjects-title {
        display: inline-block;
        position: relative;
        color: var(--color-black);
        font-family: var(--font-poppins);
        font-weight: 700;
        font-size: 20px;
        line-height: 26px;
        margin: 0;
        padding-bottom: 16px;
    }

    .subjects-title::before {
        content: '';
        position: absolute;
        bottom: 4px;
        left: 50%;
        transform: translateX(-50%);
        width: 224px;
        height: 1px;
        background-color: var(--color-black);
    }

    .subjects-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 94px;
        height: 4px;
        background-color: var(--color-black);
    }

    .carousel-wrapper {
        position: relative;
        width: 100%;
        border-top: 1px solid #000;
        border-bottom: 1px solid #000;
        display: flex;
        align-items: center;
    }

    .carousel-track {
        display: flex;
        justify-content: space-around;
        align-items: flex-start;
        padding: 40px 0;
        width: 100%;
    }

    .subject-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 15px;
        text-align: center;
        flex-shrink: 0;
    }

    .subject-icon {
        height: 80px;
        width: 80px;
        background-color: var(--color-primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 32px;
    }

    .subject-name {
        font-family: var(--font-poppins);
        font-weight: 600;
        font-size: 16px;
        color: var(--color-black);
    }

    /* Footer */
    .site-footer {
        background-color: var(--color-primary);
        color: var(--color-white);
        padding: 40px 50px;
    }

    .footer-content {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 30px;
    }

    .footer-info h4 {
        font-family: var(--font-poppins);
        font-weight: 400;
        font-size: 16px;
        line-height: 24px;
        margin: 0 0 12px 0;
    }

    .footer-info p {
        font-family: var(--font-poppins);
        font-weight: 400;
        font-size: 16px;
        line-height: 24px;
        margin: 0;
    }

    .footer-credits {
        text-align: right;
    }

    .footer-credits p {
        font-family: var(--font-poppins);
        font-weight: 400;
        font-size: 16px;
        line-height: 24px;
        margin: 0;
        margin-bottom: 16px;
    }

    .footer-credits p:last-child {
        margin-bottom: 0;
    }

    @media (max-width: 992px) {
        .footer-content {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .footer-credits {
            text-align: center;
        }
    }
</style>
@extends('layouts.guest')
<!-- Header/Navigation -->
@section('content')
    <x-nav-header />

    <!-- Hero Section -->
    <section class="section-container">
        <div class="hero-content">
            <div class="hero-image-container">
                <img src="{{ asset('images/image2.jpg') }}"
                    alt="Paseo Autonómico de la Universidad Mayor de San Simón">
            </div>
            <div class="hero-text-container">
                <p>El Paseo Autonómico de la Universidad Mayor de San Simón es uno de los espacios más emblemáticos del
                    campus, rodeado de palmeras y áreas verdes que reflejan la vida universitaria y la tradición académica
                    de la UMSS.</p>
            </div>
        </div>
    </section>

    <!-- Olympiads Section -->
    <section class="section-container olympiads-section">
        <h2 class="olympiads-title">OLIMPIADAS CIENTÍFICAS UMSS</h2>
        <p class="olympiads-description">
            La Olimpiada de Ciencias y Tecnología Oh! Sansi busca fomentar el interés por la ciencia, la tecnología y la
            innovación en estudiantes de distintos niveles educativos. La plataforma asegura un proceso transparente en
            inscripción, evaluación, publicación de resultados y reclamos, garantizando igualdad de oportunidades y
            promoviendo el desarrollo académico y científico de la comunidad.
        </p>
    </section>

    <!-- Subjects Section -->
    <section class="section-container subjects-section">
        <div class="subjects-title-wrapper">
            <h2 class="subjects-title">ÁREAS ESPECÍFICAS</h2>
        </div>
        <div class="carousel-wrapper">
            <div class="carousel-track">
                <div class="subject-item">
                    <div class="subject-icon">
                        <i class="fas fa-atom"></i>
                    </div>
                    <div class="subject-name">FÍSICA</div>
                </div>
                <div class="subject-item">
                    <div class="subject-icon">
                        <i class="fas fa-flask"></i>
                    </div>
                    <div class="subject-name">QUÍMICA</div>
                </div>
                <div class="subject-item">
                    <div class="subject-icon">
                        <i class="fas fa-dna"></i>
                    </div>
                    <div class="subject-name">BIOLOGÍA</div>
                </div>
                <div class="subject-item">
                    <div class="subject-icon">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <div class="subject-name">MATEMÁTICAS</div>
                </div>
                <div class="subject-item">
                    <div class="subject-icon">
                        <i class="fas fa-globe-americas"></i>
                    </div>
                    <div class="subject-name">GEOGRAFÍA</div>
                </div>
            </div>
        </div>
    </section>
@endsection
