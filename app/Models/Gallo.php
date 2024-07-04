<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Gallo
 * 
 * @property int $id
 * @property string|null $placa
 * @property string|null $marca_nacimiento
 * @property string|null $marca_federacion
 * @property string|null $color
 * @property string|null $cresta
 * @property string|null $fecha_nacimiento
 * @property string|null $luna
 * @property string|null $peleas
 * @property string|null $observaciones
 * 
 * @property Collection|GallosHijo[] $gallos_hijos
 * @property Collection|GallosImagene[] $gallos_imagenes
 * @property Collection|Venta[] $ventas
 *
 * @package App\Models
 */
class Gallo extends Model
{
	protected $table = 'gallos';
	public $timestamps = false;

	protected $fillable = [
		'placa',
		'marca_nacimiento',
		'marca_federacion',
		'color',
		'cresta',
		'fecha_nacimiento',
		'luna',
		'peleas',
		'observaciones',
		'estatus'
	];

	public function gallos_hijos()
	{
		return $this->hasMany(GallosHijo::class, 'hijo_id');
	}

	public function gallos_imagenes()
	{
		return $this->hasMany(GallosImagene::class);
	}

	public function ventas()
	{
		return $this->hasMany(Venta::class);
	}
}
