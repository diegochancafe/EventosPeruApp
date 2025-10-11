@php
  $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('page-script')
  @vite(['resources/assets/js/modules/app-page-categories.js'])
@endsection

@section('title', 'Categorías')

@section('content')


  <div id="content-authorized" style="display: none;">
    <h4>Categorías</h4>

    <h5>Lista de Categorías</h5>
    <!-- Permission Table -->
    <div class="card">
      <div class="card-datatable table-responsive">
        <table class="datatable-categories table border-top">
          <thead>
            <tr>
              <th>ID</th>
              <th>NOMBRE</th>
              <th>DESCRIPCIÓN</th>
              <th>ACCIONES</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td colspan="4">Cargando...</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <!--/ Permission Table -->

    <!-- Modal -->
    <div class="modal fade" id="createCategoryModal" tabindex="-1" aria-hidden="true">
      {{-- <div class="modal-dialog" role="document"> --}}
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Crear Categoría</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            </button>
          </div>
          <div class="modal-body">

            <div class="row">
              <div class="col mb-4">
                <label class="form-label">Nombre</label>
                <input type="text" id="createName" class="form-control" placeholder="Example Name">
              </div>
            </div>

            <div class="row">
              <div class="col mb-4">
                <label class="form-label">Descripción</label>
                <input type="email" id="createDescription" class="form-control"
                  placeholder="For example: For party, etc.">
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
    <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-hidden="true">
      {{-- <div class="modal-dialog" role="document"> --}}
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Actualizar Categoría</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            </button>
          </div>
          <div class="modal-body">

            <input type="text" id="editId" hidden>
            <div class="row">
              <div class="col mb-4">
                <label class="form-label">Nombre</label>
                <input type="text" id="editName" class="form-control" placeholder="Example Name">
              </div>
            </div>

            <div class="row">
              <div class="col mb-4">
                <label class="form-label">Descripción</label>
                <input type="email" id="editDescription" class="form-control"
                  placeholder="For example: For party, etc.">
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
