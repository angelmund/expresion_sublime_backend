<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class MedidasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Medidas de pieza, litro, onza, centmetros
        DB::table('medidas')->insert([
            'nombre' => 'Pieza',
            'descripcion' => 'Medida de pieza',
            'activo' => true,
        ]);
        DB::table('medidas')->insert([
            'nombre' => 'Litro',
            'descripcion' => 'Medida de litro',
            'activo' => true,
        ]);
        DB::table('medidas')->insert([
            'nombre' => 'Onza',
            'descripcion' => 'Medida de onza',
            'activo' => true,
        ]);
        DB::table('medidas')->insert([
            'nombre' => 'Centimetros',
            'descripcion' => 'Medida de centimetros',
            'activo' => true,
        ]);
    }
}
