@php
  $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('page-script')
  @vite(['resources/assets/js/modules/app-page-events.js'])
@endsection

@section('title', 'Eventos')

@section('content')
  <h4>Eventos</h4>

  <h5>Lista de Eventos</h5>
  <!-- Event Table -->
  <div class="card">
    <div class="card-datatable table-responsive">
      <table class="datatable-events table border-top">
        <thead>
          <tr>
            <th>ID</th>
            <th>CLIENTE</th>
            <th>NOMBRE</th>
            <th>DÍA EVENTO</th>
            {{-- <th>HORA INICIO</th>
            <th>HORA FIN</th> --}}
            <th>DIRECCIÓN</th>
            <th>ESTADO</th>
            <th>ACCIONES</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td colspan="7">Cargando...</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <!--/ Event Table -->


  <!-- Modal -->
  <div class="modal fade" id="createEventModal" tabindex="-1" aria-hidden="true">
    {{-- <div class="modal-dialog" role="document"> --}}
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Crear Evento</h5>
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
              <label class="form-label">Fecha</label>
              <input class="form-control" type="date" id="createEventDate">
            </div>
          </div>

          <div class="row g-6">
            <div class="col-md-6">
              <label class="form-label">Hora de inicio</label>
              <input class="form-control" type="time" id="createStartTime">
            </div>
            <div class="col-md-6">
              <label class="form-label">Hora de termino</label>
              <input class="form-control" type="time" id="createEndTime">
            </div>
          </div>
          <br>

          <div class="row">
            <div class="col mb-4">
              <label class="form-label">Dirección</label>
              <input type="text" id="createEventAddress" class="form-control" placeholder="Lorem ipsum">
            </div>
          </div>

          <div class="row">
            <div class="col mb-4">
              <label class="form-label">Servicios</label>
              <select id="createServiceSelect" class="select2 form-select" multiple="multiple">
                {{-- Options se cargan dinámicamente --}}
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

@endsection
