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

@endsection
