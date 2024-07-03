<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Cliente
 * 
 * @property int $id
 * @property string|null $nombre
 * @property string|null $telefono
 * 
 * @property Collection|Venta[] $ventas
 *
 * @package App\Models
 */
class Cliente extends Model
{
	protected $table = 'clientes';
	public $timestamps = false;

	protected $fillable = [
		'nombre',
		'telefono'
	];

	public function ventas()
	{
		return $this->hasMany(Venta::class);
	}
}
