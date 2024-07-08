<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Venta
 * 
 * @property int $id
 * @property int|null $gallo_id
 * @property int|null $cliente_id
 * @property float|null $monto
 * @property string|null $observaciones
 * @property string|null $estatus
 * @property Carbon|null $created_at
 * @property Carbon|null $update_at
 * 
 * @property Cliente|null $cliente
 * @property Gallo|null $gallo
 *
 * @package App\Models
 */
class Venta extends Model
{
	protected $table = 'ventas';
	public $timestamps = true;

	protected $casts = [
		'gallo_id' => 'int',
		'monto' => 'float',
		'update_at' => 'datetime'
	];

	protected $fillable = [
		'gallo_id',
		'nombre_cliente',
		'telefono',
		'monto',
		'observaciones',
		'estatus',
		'update_at'
	];

	// public function cliente()
	// {
	// 	return $this->belongsTo(Cliente::class);
	// }

	public function gallo()
	{
		return $this->belongsTo(Gallo::class);
	}
}
