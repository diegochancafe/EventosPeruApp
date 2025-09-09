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
  <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Descripcion</th>
      </tr>
    </thead>
    <tbody id="categoryTableBody">
      <tr>
        <td colspan="3">Cargando...</td>
      </tr>
    </tbody>
  </table>

@endsection
