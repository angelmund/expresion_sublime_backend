<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstatusVentasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('estatus_ventas')->insert([
            ['nombre' => 'Pendiente', 'color'=> 'bg-red-500', 'activo'=> true, 'created_at' => now(), 'updated_at' => now() ],
            ['nombre' => 'Pagado', 'color'=> 'bg-green-500', 'activo'=> true, 'created_at' => now(), 'updated_at' => now() ],
            ['nombre' => 'Enviado', 'color'=> 'bg-blue-500', 'activo'=> true, 'created_at' => now(), 'updated_at' => now() ],
            ['nombre' => 'Entregado', 'color'=> 'bg-purple-500', 'activo'=> true , 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Cancelado', 'color'=> 'bg-gray-500', 'activo'=> true, 'created_at' => now(), 'updated_at' => now() ],
            ['nombre' => 'Devuelto', 'color'=> 'bg-yellow-500', 'activo'=> true, 'created_at' => now(), 'updated_at' => now() ],
            ['nombre' => 'Pago Rechazado', 'color'=> 'bg-red-500', 'activo'=> true, 'created_at' => now(), 'updated_at' => now() ],
            ['nombre' => 'En Proceso', 'color'=> 'bg-orange-500', 'activo'=> true, 'created_at' => now(), 'updated_at' => now() ]
        ]);
    }
}
