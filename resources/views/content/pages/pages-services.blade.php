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

@endsection
