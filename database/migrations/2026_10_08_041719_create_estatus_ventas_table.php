<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('estatus_ventas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Ej. Pendiente, Pagado, En Diseño, Enviado, Entregado, Cancelado
            $table->string('color')->nullable(); // Útil para ponerle badges de colores en el Admin de React
            $table->boolean('activo')->default(true); // Para poder desactivar un estatus sin eliminarlo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estatus_ventas');
    }
};
