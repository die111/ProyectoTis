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

<style>
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
</style>