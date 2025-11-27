@extends('layouts.app')
@section('title', 'Editar Rol')

@section('content')

    <div class="-mx-4 lg:-mx-6 w-full">
        <div class="px-4 lg:px-6">
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-semibold text-gray-900 mb-2">Editar Rol</h1>
                    <p class="text-gray-600">Actualiza los datos del rol y sus permisos.</p>
                </div>
                <a href="{{ route('admin.roles.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Atrás
                </a>
            </div>

            <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="bg-white rounded-lg border border-gray-200 shadow-sm mb-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-user-shield text-blue-600"></i>
                            Información del Rol
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nombre del Rol <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" maxlength="20" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('name', $role->name) }}" required>
                            @error('name')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                            <textarea name="description" id="description" rows="3" maxlength="30" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description', $role->description) }}</textarea>
                            @error('description')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Permisos</label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                @foreach($permissions as $permission)
                                    @if($permission->name !== 'dashboard')
                                        <label class="flex items-center space-x-2 bg-gray-50 border border-gray-100 rounded-md px-3 py-2">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="form-checkbox h-4 w-4 text-blue-600" {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                            <span class="text-sm text-gray-800">{{ $permission->name }}</span>
                                        </label>
                                    @endif
                                @endforeach
                            </div>
                            @error('permissions')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" class="px-6 py-2 border border-gray-300 text-black rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500" onclick="window.location='{{ route('admin.roles.index') }}'">Cancelar</button>
                    <button type="submit" class="btn btn-primary bg-[#091c47] text-white px-6 py-2 rounded-md">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

@endsection
