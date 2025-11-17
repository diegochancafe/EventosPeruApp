<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\User;
use App\Models\Service;

class EventSeeder extends Seeder
{
  public function run(): void
  {
    $clients = User::where('role', 'client')->get();

    foreach ($clients as $client) {

      $event = Event::create([
        'client_id' => $client->id,
        'title' => 'Evento de ' . $client->name,
        'description' => 'Evento generado automáticamente.',
        'event_date' => now()->addDays(rand(1, 30)),
        'start_time' => '18:00:00',
        'end_time' => '23:00:00',
        'event_address' => 'Av. Los Pinos 123',
        'status' => 'pending',
      ]);

      $randomServices = Service::inRandomOrder()->take(2)->get();

      foreach ($randomServices as $srv) {
        $event->services()->attach($srv->id, [
          'status' => 'pending'
        ]);
      }
    }
  }
}
