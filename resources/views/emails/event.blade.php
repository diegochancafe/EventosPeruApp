<p>Hola {{ $event->client->name }},</p>

<p>Aquí tienes el PDF con los detalles de tu evento:</p>

<p><strong>{{ $event->title }}</strong><br>
  Fecha: {{ $event->event_date }}<br>
  Dirección: {{ $event->event_address }}</p>

<p>Gracias por confiar en nuestros servicios.</p>
