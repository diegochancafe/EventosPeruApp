<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
  public function run(): void
  {
    User::create([
      'name' => 'Admin Master',
      'email' => 'admin@eventos.com',
      'password' => Hash::make('12345678'),
      'role' => 'admin',
      'phone' => '999999999',
    ]);

    User::factory()->count(2)->create(['role' => 'provider']);
    User::factory()->count(3)->create(['role' => 'client']);
  }
}
