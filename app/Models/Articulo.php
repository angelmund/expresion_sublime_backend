<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * Class Articulo
 * 
 * @property int $id
 * @property int $categoria_id
 * @property string $nombre
 * @property float $precio_base
 * @property float|null $precio_descuento
 * @property string|null $descripcion
 * @property string|null $imagen
 * @property bool $activo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Categoria $categoria
 * @property Collection|Medida[] $medidas
 * @property Collection|DetalleVenta[] $detalle_ventas
 *
 * @package App\Models
 */
class Articulo extends Model
{
	protected $table = 'articulos';
	protected $appends = ['precio_final'];

	protected $casts = [
		'categoria_id' => 'int',
		'precio_base' => 'float',
		'precio_descuento' => 'float',
		'stock' => 'int',
		'activo' => 'bool'
	];

	protected $fillable = [
		'categoria_id',
		'nombre',
		'precio_base',
		'imagen',
		'precio_descuento',
		'descripcion',
		'stock',
		'activo'
	];

	public function categoria()
	{
		return $this->belongsTo(Categoria::class);
	}

	public function medidas()
	{
		return $this->belongsToMany(Medida::class)
					->withPivot('id', 'precio_adicional')
					->withTimestamps();
	}

	public function detalle_ventas()
	{
		return $this->hasMany(DetalleVenta::class);
	}

	protected function precioFinal(): Attribute
    {
        return Attribute::make(
            get: fn () => ($this->precio_descuento > 0) ? $this->precio_descuento : $this->precio_base,
        );
    }
}
