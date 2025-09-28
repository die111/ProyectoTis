@extends('layouts.app')
@section('title', 'Dashboard Administrador')
@section('content')
<main class="min-h-screen bg-gray-50 p-6 md:p-8">
    <h2 class="text-3xl font-normal text-gray-800 text-center mb-10 md:mb-20">Fases de la competencia</h2>
    
    <form class="flex flex-col md:flex-row gap-4 justify-center items-center mb-12 md:mb-16">
        <div class="relative w-full md:w-96">
            <div class="flex items-center bg-gray-300 rounded-lg px-4 py-2 w-full">
                <input type="search" placeholder="Encuentra la fase" 
                    class="flex-grow bg-transparent border-none outline-none text-sm text-gray-600 placeholder-gray-500">
                <img src="/page/7566567f-076f-4bd3-a701-d53236055c0b/images/249_2366.svg" 
                    alt="Search icon" class="w-5 h-5">
            </div>
        </div>
        <button type="submit" 
            class="bg-blue-600 text-white rounded-full px-6 py-2 text-sm font-medium tracking-wide hover:bg-blue-700 transition-colors">
            Buscar
        </button>
    </form>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 mb-8 md:mb-10">
        <!-- Fase 1 -->
        <article class="bg-white rounded-lg shadow-sm overflow-hidden text-center pb-5 border-t-4 border-red-500">
            <img src="/page/7566567f-076f-4bd3-a701-d53236055c0b/images/88ce9ac76eb333c02e487bcb18f66d51a75b1513.png" 
                alt="Fase 1" class="w-full h-36 object-contain my-5">
            <div class="px-4 md:px-6">
                <p class="text-gray-600 text-lg">Estado: Finalizado</p>
                <hr class="border-t-2 border-gray-300 my-3">
                <p class="text-gray-600 text-lg leading-relaxed mb-4 min-h-[60px]">
                    Clasificados a la siguiente fase: 20<br>Fecha de examen: 12/09/2025
                </p>
                <a href="#" class="inline-block bg-blue-600 text-white font-medium text-lg px-5 py-2 rounded-xl hover:bg-blue-700 transition-colors">
                    Ver
                </a>
            </div>
        </article>
        
        <!-- Fase 2 -->
        <article class="bg-white rounded-lg shadow-sm overflow-hidden text-center pb-5 border-t-4 border-blue-600">
            <img src="/page/7566567f-076f-4bd3-a701-d53236055c0b/images/b5bd955a9a1ac22de48e09f5850b65aea4ee4342.png" 
                alt="Fase 2" class="w-full h-36 object-contain my-5">
            <div class="px-4 md:px-6">
                <p class="text-gray-600 text-lg">Estado: En Proceso</p>
                <hr class="border-t-2 border-gray-300 my-3">
                <p class="text-gray-600 text-lg leading-relaxed mb-4 min-h-[60px]">
                    Clasificados a la siguiente fase: 10<br>Fecha de examen: 20/09/2025
                </p>
                <a href="#" class="inline-block bg-blue-600 text-white font-medium text-lg px-5 py-2 rounded-xl hover:bg-blue-700 transition-colors">
                    Finalizar
                </a>
            </div>
        </article>
        
        <!-- Fase 3 -->
        <article class="bg-white rounded-lg shadow-sm overflow-hidden text-center pb-5 border-t-4 border-yellow-500">
            <img src="/page/7566567f-076f-4bd3-a701-d53236055c0b/images/9881c4b5b50451ef0c06090e46ff8d1dbdb49617.png" 
                alt="Fase 3" class="w-full h-36 object-contain my-5">
            <div class="px-4 md:px-6">
                <p class="text-gray-600 text-lg">Estado: En espera</p>
                <hr class="border-t-2 border-gray-300 my-3">
                <p class="text-gray-600 text-lg leading-relaxed mb-4 min-h-[60px]">
                    Clasificados a la siguiente fase: 5<br>Fecha de examen: 30/09/2025
                </p>
                <a href="#" class="inline-block bg-blue-600 text-white font-medium text-lg px-5 py-2 rounded-xl hover:bg-blue-700 transition-colors">
                    Iniciar
                </a>
            </div>
        </article>
        
        <!-- Fase 4 -->
        <article class="bg-white rounded-lg shadow-sm overflow-hidden text-center pb-5 border-t-4 border-yellow-500">
            <img src="/page/7566567f-076f-4bd3-a701-d53236055c0b/images/5b84f0e32f1646a98474db48746149cb8fde962e.png" 
                alt="Fase 4" class="w-full h-36 object-contain my-5">
            <div class="px-4 md:px-6">
                <p class="text-gray-600 text-lg">Estado: En espera</p>
                <hr class="border-t-2 border-gray-300 my-3">
                <p class="text-gray-600 text-lg leading-relaxed mb-4 min-h-[60px]">
                    Premiación directa a los 3 primeros lugares<br>Fecha de examen: 12/10/2025
                </p>
                <a href="#" class="inline-block bg-blue-600 text-white font-medium text-lg px-5 py-2 rounded-xl hover:bg-blue-700 transition-colors">
                    Iniciar
                </a>
            </div>
        </article>
    </div>
    
    <nav class="flex justify-end items-center gap-2 md:gap-3">
        <a href="#" class="flex items-center justify-center w-9 h-9">
            <img src="/page/7566567f-076f-4bd3-a701-d53236055c0b/images/253_1656.svg" alt="Previous page">
        </a>
        <a href="#" class="flex items-center justify-center w-10 h-9 bg-black text-white rounded border border-white text-lg">1</a>
        <a href="#" class="flex items-center justify-center w-10 h-9 rounded text-lg hover:bg-gray-200">2</a>
        <a href="#" class="flex items-center justify-center w-9 h-9">
            <img src="/page/7566567f-076f-4bd3-a701-d53236055c0b/images/253_1668.svg" alt="Next page">
        </a>
    </nav>
</main>
@endsection


<style>
    
</style>
