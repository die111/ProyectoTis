@extends('layouts.app')
@section('title', 'Dashboard Administrador')
@section('content')
    <div class="container">
        <h2>Crear Nuevo Usuario</h2>
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Rol</label>
                <select class="form-select" id="role" name="role" required>
                    <option value="admin">Admin</option>
                    <option value="responsable_area">Responsable de Área</option>
                    <option value="evaluador">Evaluador</option>
                    <option value="coordinador">Coordinador</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Crear Usuario</button>
        </form>
    </div>
@endsection
