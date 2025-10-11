@php
  $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('page-script')
  @vite(['resources/assets/js/modules/app-page-users.js'])
@endsection

@section('title', 'Usuarios')

@section('content')


  <div id="content-authorized" style="display: none;">

    <div class="row g-6 mb-6">
      <div class="col-sm-6 col-xl-3">
        <div class="card">
          <div class="card-body">
            <div class="d-flex align-items-start justify-content-between">
              <div class="content-left">
                <span class="text-heading">Usuarios</span>
                <div class="d-flex align-items-center my-1">
                  <h4 id="totalCountUsers" class="mb-0 me-2">0</h4>
                  {{-- <p class="text-success mb-0">(100%)</p> --}}
                </div>
                <small class="mb-0">Total usuarios</small>
              </div>
              <div class="avatar">
                <span class="avatar-initial rounded bg-label-primary">
                  <i class="icon-base ti tabler-users icon-26px"></i>
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-xl-3">
        <div class="card">
          <div class="card-body">
            <div class="d-flex align-items-start justify-content-between">
              <div class="content-left">
                <span class="text-heading">Administradores</span>
                <div class="d-flex align-items-center my-1">
                  <h4 id="totalUsersAdmin" class="mb-0 me-2">0</h4>
                  {{-- <p class="text-success mb-0">(+95%)</p> --}}
                </div>
                <small class="mb-0">total administradores </small>
              </div>
              <div class="avatar">
                <span class="avatar-initial rounded bg-label-success">
                  <i class="icon-base ti tabler-user-check icon-26px"></i>
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-xl-3">
        <div class="card">
          <div class="card-body">
            <div class="d-flex align-items-start justify-content-between">
              <div class="content-left">
                <span class="text-heading">Proveedores</span>
                <div class="d-flex align-items-center my-1">
                  <h4 id="totalUsersProvider" class="mb-0 me-2">0</h4>
                  {{-- <p class="text-success mb-0">(0%)</p> --}}
                </div>
                <small class="mb-0">Total proveedores</small>
              </div>
              <div class="avatar">
                <span class="avatar-initial rounded bg-label-danger">
                  <i class="icon-base ti tabler-user-plus icon-26px"></i>
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-xl-3">
        <div class="card">
          <div class="card-body">
            <div class="d-flex align-items-start justify-content-between">
              <div class="content-left">
                <span class="text-heading">Clientes</span>
                <div class="d-flex align-items-center my-1">
                  <h4 id="totalUsersClient" class="mb-0 me-2">0</h4>
                  {{-- <p class="text-danger mb-0">(+6%)</p> --}}
                </div>
                <small class="mb-0">Total clientes</small>
              </div>
              <div class="avatar">
                <span class="avatar-initial rounded bg-label-warning">
                  <i class="icon-base ti tabler-user-search icon-26px"></i>
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Permission Table -->
    <div class="card">
      <div class="card-header border-bottom">
        <h5 class="card-title mb-0">Lista de Usuarios</h5>
      </div>
      <div class="card-datatable table-responsive">
        <table class="datatable-users table border-top">
          <thead>
            <tr>
              <th>ID</th>
              <th>NOMBRES</th>
              <th>EMAIL</th>
              <th>ROL</th>
              <th>CÉLULAR</th>
              <th>ACCIONES</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td colspan="6">Cargando...</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <!--/ Permission Table -->

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

    <!-- Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
      {{-- <div class="modal-dialog" role="document"> --}}
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Modificar Usuario</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            </button>
          </div>
          <div class="modal-body">
            <input type="text" id="editId" hidden>
            <div class="row">
              <div class="col mb-4">
                <label class="form-label">Nombres</label>
                <input type="text" id="editName" class="form-control" placeholder="Example Name">
              </div>
            </div>

            <div class="row">
              <div class="col mb-4">
                <label class="form-label">Email</label>
                <input type="email" id="editEmail" class="form-control" placeholder="example@gmail.com">
              </div>
            </div>

            <div class="row">
              <div class="col mb-4">
                <label for="defaultSelect" class="form-label">Rol</label>
                <select id="editRole" class="form-select">
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
                <input type="number" id="editPhone" class="form-control" placeholder="+51 999 999 999">
              </div>
            </div>


          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary" id="editButton">Actualizar</button>
          </div>
        </div>
      </div>
    </div>

  </div>

  <!-- Not Authorized -->
  <div id="content-not-authorized" style="display: none;">
    <div class="container-xxl container-p-y d-flex flex-column align-items-center justify-content-center text-center"
      style="min-height: 100vh;">
      <div class="misc-wrapper">
        <h1 class="mb-2" style="line-height: 6rem; font-size: 6rem;">403</h1>
        <h4 class="mb-2">¡No estás autorizado! 🔐</h4>
        <p class="mb-4">No tienes permiso para acceder a esta página. ¡Vuelve al inicio!</p>
        <a href="{{ url('/') }}" class="btn btn-primary mb-4">Volver al inicio</a>
        <div>
          <img src="{{ asset('assets/img/illustrations/page-misc-you-are-not-authorized.png') }}"
            alt="page-misc-not-authorized" width="170" class="img-fluid">
        </div>
      </div>
    </div>
  </div>

  <!-- /Not Authorized -->

@endsection
