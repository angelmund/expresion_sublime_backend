<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Venta
 * 
 * @property int $id
 * @property int $user_id
 * @property int $estatus_venta_id
 * @property string $folio
 * @property float $total
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 * @property EstatusVenta $estatus_venta
 * @property Collection|DetalleVenta[] $detalle_ventas
 *
 * @package App\Models
 */
class Venta extends Model
{
	protected $table = 'ventas';

	protected $casts = [
		'user_id' => 'int',
		'estatus_venta_id' => 'int',
		'total' => 'float'
	];

	protected $fillable = [
		'user_id',
		'estatus_venta_id',
		'folio',
		'total'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function estatus_venta()
	{
		return $this->belongsTo(EstatusVenta::class);
	}

	public function detalle_ventas()
	{
		return $this->hasMany(DetalleVenta::class);
	}
}
