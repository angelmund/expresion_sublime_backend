<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ArticuloMedida
 * 
 * @property int $id
 * @property int $articulo_id
 * @property int $medida_id
 * @property float $precio_adicional
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Articulo $articulo
 * @property Medida $medida
 *
 * @package App\Models
 */
class ArticuloMedida extends Model
{
	protected $table = 'articulo_medida';

	protected $casts = [
		'articulo_id' => 'int',
		'medida_id' => 'int',
		'precio_adicional' => 'float'
	];

	protected $fillable = [
		'articulo_id',
		'medida_id',
		'precio_adicional'
	];

	public function articulo()
	{
		return $this->belongsTo(Articulo::class);
	}

	public function medida()
	{
		return $this->belongsTo(Medida::class);
	}
}
