@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
  <div class="max-w-3xl mx-auto bg-white shadow rounded-lg p-6">
    <h2 class="text-2xl font-semibold mb-4">Editar Perfil</h2>

    @if(session('success'))
      <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium">Nombre</label>
          <input type="text" name="name" value="{{ old('name', $user->name) }}" class="input w-full" required>
          @error('name')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>

        <div>
          <label class="block text-sm font-medium">Email</label>
          <input type="email" name="email" value="{{ old('email', $user->email) }}" class="input w-full" required>
          @error('email')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>

        <div>
          <label class="block text-sm font-medium">Apellido Paterno</label>
          <input type="text" name="last_name_father" value="{{ old('last_name_father', $user->last_name_father) }}" class="input w-full">
        </div>

        <div>
          <label class="block text-sm font-medium">Apellido Materno</label>
          <input type="text" name="last_name_mother" value="{{ old('last_name_mother', $user->last_name_mother) }}" class="input w-full">
        </div>

        <div>
          <label class="block text-sm font-medium">Teléfono</label>
          <input type="text" name="telephone_number" value="{{ old('telephone_number', $user->telephone_number) }}" class="input w-full">
        </div>

        <div>
          <label class="block text-sm font-medium">C.I.</label>
          <input type="text" name="ci" value="{{ old('ci', $user->ci) }}" class="input w-full" disabled>
        </div>

        <div class="md:col-span-2">
          <label class="block text-sm font-medium">Dirección</label>
          <input type="text" name="address" value="{{ old('address', $user->address) }}" class="input w-full">
        </div>

        <div>
          <label class="block text-sm font-medium">Fecha de nacimiento</label>
          <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth) }}" class="input w-full">
        </div>

        <div class="md:col-span-2">
          <label class="block text-sm font-medium">Foto de perfil</label>
          <input type="file" name="profile_photo" accept="image/*" class="input w-full">
          @if($user->profile_photo)
            <div class="mt-2">
              <img src="{{ asset('storage/'.$user->profile_photo) }}" alt="Foto" class="w-24 h-24 rounded-full object-cover">
            </div>
          @endif
          @error('profile_photo')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
      </div>

      <div class="mt-6 flex gap-2">
        <button type="submit" class="btn btn-primary">Guardar cambios</button>
        <a href="{{ route('profile.show') }}" class="btn btn-secondary">Cancelar</a>
      </div>
    </form>
  </div>
</div>
@endsection
