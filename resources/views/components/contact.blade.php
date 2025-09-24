@props([
  'email' => 'informaciones@umss.edu.bo',
  'phone' => '(+591) 4 4525161',
  'address' => 'Av. Oquendo y Jordan',
  'facebook' => '#',
  'instagram'=> '#',
  'tiktok'   => '#',
])

<section class="max-w-5xl mx-auto px-4 py-10">

  {{-- Título CONTACTOS con estilo de ÁREAS ESPECÍFICAS --}}
  <div class="subjects-title-wrapper">
      <h2 class="subjects-title">CONTACTOS</h2>
  </div>

  {{-- Grid de información de contacto --}}
  <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-10 text-center">
    <div>
      <div class="mx-auto mb-3 h-14 w-14 grid place-content-center text-3xl">📧</div>
      <p class="font-semibold">Correo:</p>
      <p class="text-sm">{{ $email }}</p>
    </div>

    <div>
      <div class="mx-auto mb-3 h-14 w-14 grid place-content-center text-3xl">☎️</div>
      <p class="font-semibold">Teléfono:</p>
      <p class="text-sm">Telf: {{ $phone }}</p>
    </div>

    <div>
      <div class="mx-auto mb-3 h-14 w-14 grid place-content-center text-3xl">📍</div>
      <p class="font-semibold">
        <a href="https://www.google.com/maps/place/UMSS,+Cochabamba/@-17.3945989,-66.1504418,1044m/data=!3m2!1e3!4b1!4m6!3m5!1s0x93e373f94e9edddf:0xd6a7bea9d74e780d!8m2!3d-17.394604!4d-66.1478669!16s%2Fg%2F1ptyht25r?entry=ttu&g_ep=EgoyMDI1MDkyMS4wIKXMDSoASAFQAw%3D%3D" target="_blank" rel="noopener noreferrer">
          Dirección:
        </a>
      </p>
      <p class="text-sm">{{ $address }}</p>
    </div>
  </div>

  {{-- Redes sociales --}}
  <div class="mt-10 flex items-center justify-center gap-8 text-3xl">
  <a href="{{ $facebook }}" aria-label="Facebook"
     class="text-gray-700 hover:text-[#1877F2] transition">
    <i class="fa-brands fa-facebook"></i>
  </a>
  

  <a href="{{ $instagram }}" aria-label="Instagram"
     class="text-gray-700 hover:text-[#E4405F] transition">
    <i class="fa-brands fa-instagram"></i>
  </a>

  <a href="{{ $tiktok }}" aria-label="TikTok"
     class="text-gray-700 hover:text-black transition">
    <i class="fa-brands fa-tiktok"></i>
  </a>
</div>

  <p class="mt-4 text-center text-sm">Universidad Mayor de San Simón</p>
</section>