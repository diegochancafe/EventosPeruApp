<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Boleta del Evento</title>
  <style>
    body {
      font-family: DejaVu Sans, sans-serif;
    }

    .title {
      font-size: 22px;
      font-weight: bold;
      margin-bottom: 10px;
    }

    .section-title {
      font-size: 16px;
      margin-top: 20px;
      font-weight: bold;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    table,
    th,
    td {
      border: 1px solid #333;
    }

    th,
    td {
      padding: 8px;
      text-align: left;
    }
  </style>
</head>

<body>

  <div class="title">Boleta del Evento</div>

  <p><strong>Título:</strong> {{ $event->title }}</p>
  <p><strong>Descripción:</strong> {{ $event->description }}</p>
  <p><strong>Fecha:</strong> {{ $event->event_date }}</p>
  <p><strong>Hora:</strong> {{ $event->start_time }} - {{ $event->end_time }}</p>
  <p><strong>Dirección:</strong> {{ $event->event_address }}</p>

  <div class="section-title">Servicios del Evento</div>

  <table>
    <thead>
      <tr>
        <th>Servicio</th>
        <th>Descripción</th>
        <th>Precio (S/.)</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($event->services as $service)
        @php
          // Tomar SIEMPRE el precio del pivote
          $unit = $service->pivot->price ?? ($service->price ?? 0);
        @endphp
        <tr>
          <td>{{ $service->name }}</td>
          <td>{{ $service->description }}</td>
          <td>{{ number_format($service->price, 2) }}</td>
        </tr>
      @endforeach
      {{-- Fila TOTAL --}}
      @php
        $totalGeneral = $event->services->sum(function ($s) {
            $price = $s->pivot->price ?? ($s->price ?? 0);
            $qty = $s->pivot->quantity ?? 1;
            return $price * $qty;
        });
      @endphp

      <tr>
        <td colspan="2" style="text-align: right; font-weight: bold; padding: 8px;">
          TOTAL
        </td>
        <td style="font-weight: bold; padding: 8px;">
          S/ {{ number_format($totalGeneral, 2) }}
        </td>
      </tr>
    </tbody>
  </table>

</body>

</html>
