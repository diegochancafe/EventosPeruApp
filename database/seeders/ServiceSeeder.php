<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
  public function run(): void
  {
    $services = [
      [
        'title' => 'Buffet Criollo',
        'description' => 'Servicio completo de comida peruana para eventos.',
        'price' => 1500,
        'category_id' => 1
      ],
      [
        'title' => 'Coffee Break Ejecutivo',
        'description' => 'Mesa con café, infusiones, snacks y pastelería.',
        'price' => 450,
        'category_id' => 1
      ],
      [
        'title' => 'DJ Profesional (3 horas)',
        'description' => 'Incluye consola, parlantes y luces básicas.',
        'price' => 800,
        'category_id' => 2
      ],
      [
        'title' => 'Saxofonista',
        'description' => 'Presentación musical elegante para recepción.',
        'price' => 350,
        'category_id' => 2
      ],
      [
        'title' => 'Decoración Temática de Cumpleaños',
        'description' => 'Globos, mesa principal y fondo temático.',
        'price' => 650,
        'category_id' => 3
      ],
      [
        'title' => 'Decoración Floral para Matrimonio',
        'description' => 'Arreglos florales para ceremonia y recepción.',
        'price' => 1200,
        'category_id' => 3
      ],
      [
        'title' => 'Fotografía Profesional',
        'description' => 'Cobertura completa del evento, edición y entrega digital.',
        'price' => 500,
        'category_id' => 4
      ],
      [
        'title' => 'Grabación en Video HD',
        'description' => 'Cobertura total del evento y video final editado.',
        'price' => 900,
        'category_id' => 4
      ],
      [
        'title' => 'Show Infantil',
        'description' => 'Animación infantil con juegos y música.',
        'price' => 400,
        'category_id' => 5
      ],
      [
        'title' => 'Hora Loca',
        'description' => 'Personajes, bailarines y accesorios para animación.',
        'price' => 600,
        'category_id' => 5
      ],
    ];

    foreach ($services as $srv) {
      Service::create([
        ...$srv,
        'user_id' => 1,
        'active' => true
      ]);
    }
  }
}
