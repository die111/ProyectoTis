@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
  <div class="max-w-3xl mx-auto bg-white shadow rounded-lg p-6">
    @if(session('success'))
      <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif
    <h2 class="text-2xl font-semibold mb-4">Perfil de Usuario</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
      <div class="col-span-1 flex items-center justify-center">
        @if($user->profile_photo)
          <img src="{{ asset('storage/'.$user->profile_photo) }}" alt="Foto" class="w-32 h-32 rounded-full object-cover">
        @else
          <div class="w-32 h-32 rounded-full bg-gray-200 flex items-center justify-center text-gray-600">
            <i class="fas fa-user text-2xl"></i>
          </div>
        @endif
      </div>
      <div class="col-span-2 space-y-1">
        <p><strong>Nombre:</strong> {{ $user->name }} {{ $user->last_name_father }} {{ $user->last_name_mother }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Código:</strong> {{ $user->user_code ?? '-' }}</p>
  <p><strong>Rol:</strong> {{ $roleName ?? ($user->role?->name ?? '-') }}</p>
  <p><strong>Área:</strong> {{ $areaName ?? ($user->area?->name ?? '-') }}</p>
        <p><strong>Teléfono:</strong> {{ $user->telephone_number ?? '-' }}</p>
        <p><strong>C.I.:</strong> {{ $user->ci ?? '-' }}</p>
        <p><strong>Dirección:</strong> {{ $user->address ?? '-' }}</p>
        <p><strong>Fecha de nacimiento:</strong> {{ $user->date_of_birth ?? '-' }}</p>
      </div>
    </div>

    <div class="mt-6 flex gap-2">
      @if(Route::has('profile.edit'))
        <a href="{{ route('profile.edit') }}" class="btn btn-primary">Editar perfil</a>
      @endif
  <a href="{{ route('dashboard') }}" class="btn btn-secondary">Volver</a>
    </div>
  </div>
</div>
@endsection
