<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Categoria
 * 
 * @property int $id
 * @property string $nombre
 * @property string $slug
 * @property string|null $descripcion
 * @property string|null $imagen
 * @property bool $activo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Articulo[] $articulos
 *
 * @package App\Models
 */
class Categoria extends Model
{
	protected $table = 'categorias';

	protected $casts = [
		'activo' => 'bool'
	];

	protected $fillable = [
		'nombre',
		'slug',
		'descripcion',
		'imagen',
		'activo'
	];

	public function articulos()
	{
		return $this->hasMany(Articulo::class);
	}
}
