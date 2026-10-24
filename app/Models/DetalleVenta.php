<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DetalleVenta
 * 
 * @property int $id
 * @property int $venta_id
 * @property int $articulo_id
 * @property int|null $medida_id
 * @property int|null $tipo_personalizacion_id
 * @property int $cantidad
 * @property float $precio_unitario
 * @property string|null $notas_diseno
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Venta $venta
 * @property Articulo $articulo
 * @property Medida|null $medida
 * @property TiposPersonalizacion|null $tipos_personalizacion
 *
 * @package App\Models
 */
class DetalleVenta extends Model
{
	protected $table = 'detalle_ventas';

	protected $casts = [
		'venta_id' => 'int',
		'articulo_id' => 'int',
		'medida_id' => 'int',
		'tipo_personalizacion_id' => 'int',
		'cantidad' => 'int',
		'precio_unitario' => 'float'
	];

	protected $fillable = [
		'venta_id',
		'articulo_id',
		'medida_id',
		'tipo_personalizacion_id',
		'cantidad',
		'precio_unitario',
		'notas_diseno'
	];

	public function venta()
	{
		return $this->belongsTo(Venta::class);
	}

	public function articulo()
	{
		return $this->belongsTo(Articulo::class);
	}

	public function medida()
	{
		return $this->belongsTo(Medida::class);
	}

	public function tipos_personalizacion()
	{
		return $this->belongsTo(TiposPersonalizacion::class, 'tipo_personalizacion_id');
	}
}
