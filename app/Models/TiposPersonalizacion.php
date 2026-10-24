<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TiposPersonalizacion
 * 
 * @property int $id
 * @property string $nombre
 * @property float $costo_adicional
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|DetalleVenta[] $detalle_ventas
 *
 * @package App\Models
 */
class TiposPersonalizacion extends Model
{
	protected $table = 'tipos_personalizacion';

	protected $casts = [
		'costo_adicional' => 'float'
	];

	protected $fillable = [
		'nombre',
		'costo_adicional'
	];

	public function detalle_ventas()
	{
		return $this->hasMany(DetalleVenta::class, 'tipo_personalizacion_id');
	}
}
