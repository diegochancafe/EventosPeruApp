@php
  $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('page-script')
  @vite(['resources/assets/js/modules/app-page-home.js'])
@endsection

@section('title', 'Home')

@section('content')
  <h4>Home Page</h4>
  <p>Welcome to Eventos Perú Admin Dashboard</p>
  {{-- <p>For more layout options refer <a
      href="{{ config('variables.documentation') ? config('variables.documentation') . '/laravel-introduction.html' : '#' }}"
      target="_blank" rel="noopener noreferrer">documentation</a>.</p> --}}
  <div class="col-12" id="events-overview-container">
    <div class="card h-100">
      <div class="card-header d-flex align-items-center justify-content-between">
        <div class="card-title mb-0">
          <h5 class="m-0 me-2">Resumen de Ingresos por Estado del Evento</h5>
        </div>
      </div>

      <div class="card-body">

        <!-- Labels -->
        <div class="d-none d-lg-flex events-progress-labels mb-3" id="events-labels"></div>

        <!-- Progress Bar -->
        <div class="progress rounded-3 mb-4" style="height: 46px;" id="events-progress"></div>

        <!-- Table -->
        <div class="table-responsive">
          <table class="table card-table">
            <tbody id="events-table-body"></tbody>
          </table>
        </div>

      </div>
    </div>
  </div>

  <br>
  <div class="row">

    <!-- SERVICE USAGE DONUT -->
    <div class="col-12">
      <div class="card h-100">
        <div class="card-header d-flex align-items-center justify-content-between">
          <div class="card-title mb-0">
            <h5 class="m-0 me-2">Uso de Servicios</h5>
          </div>

        </div>

        <div class="card-body">
          <div id="servicesUsageDonut"></div>
        </div>
      </div>
    </div>

  </div>

  <style>
    .events-progress-label {
      text-align: center;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .status-dot {
      width: 12px;
      height: 12px;
      border-radius: 50%;
      display: inline-block;
    }
  </style>

@endsection
