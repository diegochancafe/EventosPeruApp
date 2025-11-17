@php
  $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('page-script')
  @vite(['resources/assets/js/modules/app-page-ratings.js'])
@endsection

@section('title', 'Ratings')

@section('content')
  <div id="content-authorized" style="display: none;">
    <h4>Ratings</h4>

    <h5>Lista de Ratings</h5>
    <div class="card">
      <div class="card-datatable table-responsive">
        <table class="datatable-ratings table border-top">
          <thead>
            <tr>
              <th>ID</th>
              <th>Cliente</th>
              <th>Evento</th>
              <th>Score</th>
              <th>Comentario</th>
              <th>Acciones</th>
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

    <!-- Modal Crear Rating -->
    <div class="modal fade" id="createRatingModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Crear Rating</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col mb-4">
                <label for="defaultSelect" class="form-label">Evento</label>
                <select id="createEvent" class="form-select">
                </select>
              </div>
            </div>

            <div class="row">
              <div class="col mb-4">
                <label class="form-label">Score</label>
                <input type="number" id="createScore" class="form-control" min="1" max="5">
              </div>
            </div>
            <div class="row">
              <div class="col mb-4">
                <label class="form-label">Comentario</label>
                <textarea id="createComment" class="form-control" placeholder="Comentario (opcional)"></textarea>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary" id="createRatingButton">Guardar</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Editar Rating -->
    <div class="modal fade" id="editRatingModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Actualizar Rating</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>


          <div class="modal-body">
            <input type="hidden" id="editRatingId">
            <div class="row">
              <div class="col mb-4">
                <label for="defaultSelect" class="form-label">Evento</label>
                <select id="editEvent" class="form-select">
                </select>
              </div>
            </div>

            <div class="row">
              <div class="col mb-4">
                <label class="form-label">Score</label>
                <input type="number" id="editScore" class="form-control" min="1" max="5">
              </div>
            </div>
            <div class="row">
              <div class="col mb-4">
                <label class="form-label">Comentario</label>
                <textarea id="editComment" class="form-control" placeholder="Comentario (opcional)"></textarea>
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary" id="editRatingButton">Actualizar</button>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection
