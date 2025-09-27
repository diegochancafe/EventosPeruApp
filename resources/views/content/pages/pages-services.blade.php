@php
  $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('page-script')
  @vite(['resources/assets/js/modules/app-page-services.js'])
@endsection

@section('title', 'Servicios')

@section('content')
  <h4>Servicios</h4>

  <h5>Lista de Servicios</h5>
  <!-- Permission Table -->
  <div class="card">
    <div class="card-datatable table-responsive">
      <table class="datatable-services table border-top">
        <thead>
          <tr>
            <th>ID</th>
            <th>CATEGORÍA</th>
            <th>SERVICIO</th>
            <th>DESCRIPCIÓN</th>
            <th>PRECIO</th>
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
  <div class="modal fade" id="createServiceModal" tabindex="-1" aria-hidden="true">
    {{-- <div class="modal-dialog" role="document"> --}}
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Crear Servicio</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
          </button>
        </div>
        <div class="modal-body">

          <div class="row">
            <div class="col mb-4">
              <label class="form-label">Nombre</label>
              <input type="text" id="createTitle" class="form-control" placeholder="Lorem ipsum">
            </div>
          </div>

          <div class="row">
            <div class="col mb-4">
              <label class="form-label">Descripción</label>
              <input type="text" id="createDescription" class="form-control"
                placeholder="Lorem ipsum dolor sit amet...">
            </div>
          </div>

          <div class="row">
            <div class="col mb-4">
              <label class="form-label">Precio</label>
              <input type="number" id="createPrice" class="form-control" placeholder="0.00">
            </div>
          </div>

          <div class="row">
            <div class="col mb-4">
              <label for="defaultSelect" class="form-label">Categoría</label>
              <select id="createCategory" class="form-select">
              </select>
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
  <div class="modal fade" id="editServiceModal" tabindex="-1" aria-hidden="true">
    {{-- <div class="modal-dialog" role="document"> --}}
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Modificar Servicio</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
          </button>
        </div>
        <div class="modal-body">
          <input type="text" id="editId" hidden>

          <div class="row">
            <div class="col mb-4">
              <label class="form-label">Nombre</label>
              <input type="text" id="editTitle" class="form-control">
            </div>
          </div>

          <div class="row">
            <div class="col mb-4">
              <label class="form-label">Descripción</label>
              <input type="text" id="editDescription" class="form-control">
            </div>
          </div>

          <div class="row">
            <div class="col mb-4">
              <label class="form-label">Precio</label>
              <input type="number" id="editPrice" class="form-control">
            </div>
          </div>

          <div class="row">
            <div class="col mb-4">
              <label for="defaultSelect" class="form-label">Categoría</label>
              <select id="editCategory" class="form-select">
              </select>
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


@endsection
