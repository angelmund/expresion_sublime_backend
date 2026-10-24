<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArticuloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('articulos')->insert([
            [
                'nombre' => 'Taza mágica',
                'descripcion' => 'Taza mágica con diseño único.',
                'precio_base' => 150,
                'precio_descuento' => 130,
                'activo' => 4,
                'imagen' => 'taza_magica.jpg',
                'categoria_id' => 1,
            ],
            [
                'nombre' => 'Taza de cerámica',
                'descripcion' => 'Taza de cerámica con diseño elegante.',
                'precio_base' => 60,
                'precio_descuento' => 40,
                'activo' => 4,
                'imagen' => 'taza_ceramica.jpg',
                'categoria_id' => 1,
            ],
            [
                'nombre' => 'Llavero personalizado',
                'descripcion' => 'Llavero con diseño personalizado.',
                'precio_base' => 79.99,
                'precio_descuento' => 50,
                'activo' => 5,
                'imagen' => 'llavero_personalizado.jpg',
                'categoria_id' => 1,
            ],
        ]);
    }
}
