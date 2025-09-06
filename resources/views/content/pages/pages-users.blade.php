@php
  $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('page-script')
  @vite(['resources/assets/js/modules/app-page-users.js'])
@endsection

@section('title', 'Usuarios')

@section('content')
  <h4>Usuarios</h4>

  <h5>Lista de Usuarios</h5>
  <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Email</th>
      </tr>
    </thead>
    <tbody id="usersTableBody">
      <tr>
        <td colspan="3">Cargando...</td>
      </tr>
    </tbody>
  </table>

@endsection
