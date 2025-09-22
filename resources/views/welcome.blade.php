@extends('layouts.guest')

@section('content')
<x-nav-header />

<!-- Hero Section -->
<section class="section-container">
    <div class="hero-content">
        <div class="hero-image-container">
            <img src="{{ asset('images/image2.jpg') }}" alt="Paseo Autonómico de la Universidad Mayor de San Simón">
        </div>
        <div class="hero-text-container">
            <p>El Paseo Autonómico de la Universidad Mayor de San Simón es uno de los espacios más emblemáticos del campus, rodeado de palmeras y áreas verdes que reflejan la vida universitaria y la tradición académica de la UMSS.</p>
        </div>
    </div>
</section>

<!-- Olympiads Section -->
<section class="section-container olympiads-section">
    <h2 class="olympiads-title">OLIMPIADAS CIENTÍFICAS UMSS</h2>
    <p class="olympiads-description">
        La Olimpiada de Ciencias y Tecnología Oh! Sansi busca fomentar el interés por la ciencia, la tecnología y la innovación en estudiantes de distintos niveles educativos. La plataforma asegura un proceso transparente en inscripción, evaluación, publicación de resultados y reclamos, garantizando igualdad de oportunidades y promoviendo el desarrollo académico y científico de la comunidad.
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
        padding: 40px 20px;
    }

    @media (min-width: 768px) {
        .section-container {
            padding: 50px 30px;
        }
    }

    @media (min-width: 992px) {
        .section-container {
            padding: 60px 50px;
        }
    }

    /* Hero Section */
    .hero-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 20px;
    }

    @media (min-width: 992px) {
        .hero-content {
            flex-direction: row;
            align-items: center;
            gap: 27px;
        }
    }

    .hero-image-container {
        width: 100%;
        max-width: 500px;
    }

    @media (min-width: 992px) {
        .hero-image-container {
            flex: 0 0 379px;
            max-width: 379px;
        }
    }

    .hero-image-container img {
        width: 100%;
        height: auto;
        display: block;
        border-radius: 10px;
    }

    .hero-text-container {
        width: 100%;
    }

    @media (min-width: 992px) {
        .hero-text-container {
            flex: 1;
        }
    }

    .hero-text-container p {
        color: var(--color-text-dark);
        font-family: var(--font-mulish);
        font-weight: 400;
        font-size: 16px;
        line-height: 1.5;
        margin: 0;
        text-align: center;
    }

    @media (min-width: 768px) {
        .hero-text-container p {
            font-size: 18px;
            text-align: left;
        }
    }

    @media (min-width: 992px) {
        .hero-text-container p {
            font-size: 21px;
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
        font-size: 18px;
        line-height: 1.3;
        margin: 0 0 25px 0;
    }

    @media (min-width: 768px) {
        .olympiads-title {
            font-size: 20px;
            margin-bottom: 30px;
        }
    }

    .olympiads-description {
        max-width: 1392px;
        margin: 0 auto;
        color: var(--color-text-dark);
        font-family: var(--font-mulish);
        font-weight: 400;
        font-size: 16px;
        line-height: 1.5;
    }

    @media (min-width: 768px) {
        .olympiads-description {
            font-size: 18px;
        }
    }

    @media (min-width: 992px) {
        .olympiads-description {
            font-size: 21px;
        }
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
        margin-bottom: 30px;
    }

    @media (min-width: 768px) {
        .subjects-title-wrapper {
            margin-bottom: 40px;
        }
    }

    .subjects-title {
        display: inline-block;
        position: relative;
        color: var(--color-black);
        font-family: var(--font-poppins);
        font-weight: 700;
        font-size: 18px;
        line-height: 1.3;
        margin: 0;
        padding-bottom: 16px;
    }

    @media (min-width: 768px) {
        .subjects-title {
            font-size: 20px;
        }
    }

    .subjects-title::before {
        content: '';
        position: absolute;
        bottom: 4px;
        left: 50%;
        transform: translateX(-50%);
        width: 180px;
        height: 1px;
        background-color: var(--color-black);
    }

    @media (min-width: 768px) {
        .subjects-title::before {
            width: 224px;
        }
    }

    .subjects-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 4px;
        background-color: var(--color-black);
    }

    @media (min-width: 768px) {
        .subjects-title::after {
            width: 94px;
        }
    }

    .carousel-wrapper {
        position: relative;
        width: 100%;
        border-top: 1px solid #000;
        border-bottom: 1px solid #000;
        display: flex;
        align-items: center;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
    }

    .carousel-wrapper::-webkit-scrollbar {
        display: none;
    }

    .carousel-track {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 30px 20px;
        width: 100%;
        min-width: min-content;
    }

    @media (min-width: 768px) {
        .carousel-track {
            justify-content: space-around;
            padding: 40px 0;
        }
    }

    .subject-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
        text-align: center;
        flex-shrink: 0;
        margin: 0 15px;
        min-width: 80px;
    }

    @media (min-width: 768px) {
        .subject-item {
            margin: 0;
            gap: 15px;
        }
    }

    .subject-icon {
        height: 60px;
        width: 60px;
        background-color: var(--color-primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
    }

    @media (min-width: 768px) {
        .subject-icon {
            height: 80px;
            width: 80px;
            font-size: 32px;
        }
    }

    .subject-name {
        font-family: var(--font-poppins);
        font-weight: 600;
        font-size: 14px;
        color: var(--color-black);
    }

    @media (min-width: 768px) {
        .subject-name {
            font-size: 16px;
        }
    }
</style>
