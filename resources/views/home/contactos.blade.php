@extends('layouts.guest'){{-- Layout público que ya incluye <x-nav-header /> y footer --}}
@section('title', 'Contactos — Oh! SanSi')

@section('content')
    <!-- Hero Section -->
    @include('home.partials.hero-section')

    {{-- 2) Sección de Contactos debajo del hero --}}
    <x-contact
        :email="$contact['email']"
        :phone="$contact['phone']"
        :address="$contact['address']"
        :facebook="$contact['facebook']"
        :instagram="$contact['instagram']"
        :tiktok="$contact['tiktok']"
    />
@endsection

