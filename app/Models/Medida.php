<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Medida
 * 
 * @property int $id
 * @property string $nombre
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Articulo[] $articulos
 * @property Collection|DetalleVenta[] $detalle_ventas
 *
 * @package App\Models
 */
class Medida extends Model
{
	protected $table = 'medidas';

	protected $fillable = [
		'nombre',
		'descripcion',
		'activo'
	];

	public function articulos()
	{
		return $this->belongsToMany(Articulo::class)
					->withPivot('id', 'precio_adicional')
					->withTimestamps();
	}

	public function detalle_ventas()
	{
		return $this->hasMany(DetalleVenta::class);
	}
}
