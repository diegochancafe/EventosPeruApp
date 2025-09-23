@php
  $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('page-script')
  @vite(['resources/assets/js/modules/app-page-categories.js'])
@endsection

@section('title', 'Categorías')

@section('content')
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
              <input type="email" id="editDescription" class="form-control" placeholder="For example: For party, etc.">
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
