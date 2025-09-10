@php
  $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('page-script')
  @vite(['resources/assets/js/modules/app-page-users.js'])
@endsection

@section('title', 'Usuarios')

@section('content')
  <h4>Usuarios</h4>

  <h5>Lista de Usuarios</h5>
  {{-- <table class="table datatable-users" id="datatable-users">
    <thead>
      <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Email</th>
      </tr>
    </thead>
    <tbody id="usersTableBody">
      <tr>
        <td colspan="3">Cargando...</td>
      </tr>
    </tbody>
  </table> --}}

  <!-- Permission Table -->
  <div class="card">
    <div class="card-datatable table-responsive">
      <table class="datatable-users table border-top">
        <thead>
          <tr>
            <th>ID</th>
            <th>NOMBRES</th>
            <th>EMAIL</th>
            <th>ROL</th>
            <th>CÉLULAR</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td colspan="5">Cargando...</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <!--/ Permission Table -->

  <!-- Button trigger modal -->
  {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
    Launch demo modal
  </button> --}}

  <!-- Modal -->
  <div class="modal fade" id="createUserModal" tabindex="-1" aria-hidden="true">
    {{-- <div class="modal-dialog" role="document"> --}}
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Crear Usuario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
          </button>
        </div>
        <div class="modal-body">

          <div class="row">
            <div class="col mb-4">
              <label class="form-label">Nombres</label>
              <input type="text" id="createName" class="form-control" placeholder="Example Name">
            </div>
          </div>

          <div class="row">
            <div class="col mb-4">
              <label class="form-label">Email</label>
              <input type="email" id="createEmail" class="form-control" placeholder="example@gmail.com">
            </div>
          </div>

          <div class="row">
            <div class="col mb-4">
              <label class="form-label">Password</label>
              <input type="password" id="createPassword" class="form-control" placeholder="********">
            </div>
          </div>

          <div class="row">
            <div class="col mb-4">
              <label for="defaultSelect" class="form-label">Rol</label>
              <select id="createRole" class="form-select">
                <option value="">Seleccionar</option>
                <option value="admin">Administrador</option>
                <option value="client">Cliente</option>
                <option value="provider">Proveedor</option>
              </select>
            </div>
          </div>

          <div class="row">
            <div class="col mb-4">
              <label class="form-label">Célular</label>
              <input type="number" id="createPhone" class="form-control" placeholder="+51 999 999 999">
            </div>
          </div>


        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" id="createButton">Guardar cambios</button>
        </div>
      </div>
    </div>
  </div>

@endsection
