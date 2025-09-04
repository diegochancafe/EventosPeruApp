@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('page-script')
@vite(['resources/assets/js/app-page-home.js'])
@endsection

@section('title', 'Home')

@section('content')
<h4>Home Page</h4>
<p>For more layout options refer <a href="{{ config('variables.documentation') ? config('variables.documentation').'/laravel-introduction.html' : '#' }}" target="_blank" rel="noopener noreferrer">documentation</a>.</p>

<h5>Lista de Usuarios</h5>
<table class="table">
  <thead>
    <tr>
      <th>ID</th>
      <th>Nombre</th>
      <th>Email</th>
    </tr>
  </thead>
  <tbody id="userTableBody">
    <tr>
      <td colspan="3">Cargando...</td>
    </tr>
  </tbody>
</table>

@endsection