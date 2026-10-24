<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EstatusVenta
 * 
 * @property int $id
 * @property string $nombre
 * @property string|null $color
 * @property bool $activo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Venta[] $ventas
 *
 * @package App\Models
 */
class EstatusVenta extends Model
{
	protected $table = 'estatus_ventas';

	protected $casts = [
		'activo' => 'bool'
	];

	protected $fillable = [
		'nombre',
		'color',
		'activo'
	];

	public function ventas()
	{
		return $this->hasMany(Venta::class);
	}
}
