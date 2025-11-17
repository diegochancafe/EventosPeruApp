<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
  public function run(): void
  {
    $categories = [
      ['name' => 'Catering', 'description' => 'Servicios de comida y bebida'],
      ['name' => 'Música', 'description' => 'DJ, músicos y sonido'],
      ['name' => 'Decoración', 'description' => 'Decoración temática y floral'],
      ['name' => 'Fotografía', 'description' => 'Servicios de foto y video'],
      ['name' => 'Entretenimiento', 'description' => 'Shows, animaciones y dinámicas'],
    ];

    foreach ($categories as $cat) {
      Category::create([
        ...$cat,
        'user_id' => 1
      ]);
    }
  }
}
