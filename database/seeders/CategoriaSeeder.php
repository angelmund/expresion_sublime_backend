<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear 5 categorias de camisa, termo, gorra, taza y llaveros
          DB::table('categorias')->insert([
            'nombre' => 'Camisa',
            'slug' => 'camisa',
            'descripcion' => 'Categoria de camisas',
            'imagen' => '',
            'activo' => true,
        ]);
          DB::table('categorias')->insert([
            'nombre' => 'Termo',
            'slug' => 'termo',
            'descripcion' => 'Categoria de termos',
            'imagen' => '',
            'activo' => true,
        ]);
          DB::table('categorias')->insert([
            'nombre' => 'Gorra',
            'slug' => 'gorra',
            'descripcion' => 'Categoria de gorras',
            'imagen' => '',
            'activo' => true,
        ]);
          DB::table('categorias')->insert([
            'nombre' => 'Taza',
            'slug' => 'taza',
            'descripcion' => 'Categoria de tazas',
            'imagen' => '',
            'activo' => true,
        ]);
          DB::table('categorias')->insert([
            'nombre' => 'Llaveros',
            'slug' => 'llaveros',
            'descripcion' => 'Categoria de llaveros',
            'imagen' => '',
            'activo' => true,
        ]);
    }
}
