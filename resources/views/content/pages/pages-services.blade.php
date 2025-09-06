@php
  $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('page-script')
  @vite(['resources/assets/js/modules/app-page-home.js'])
@endsection

@section('title', 'Servicios')

@section('content')
  <h4>Servicios</h4>

  <h5>Lista de Servicios</h5>
  <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Email</th>
      </tr>
    </thead>
    <tbody id="serviceTableBody">
      <tr>
        <td colspan="3">Cargando...</td>
      </tr>
    </tbody>
  </table>

@endsection
