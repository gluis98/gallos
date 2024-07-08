<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Gallina
 * 
 * @property int $id
 * @property int|null $padre_id
 * @property int|null $madre_id
 * @property string|null $placa
 * @property string|null $marca_nacimiento
 * @property string|null $marca_federacion
 * @property string|null $color
 * @property string|null $cresta
 * @property string|null $fecha_nacimiento
 * @property string|null $luna
 * @property string|null $observaciones
 * 
 * @property Collection|GallinasImagene[] $gallinas_imagenes
 * @property Collection|GallosHijo[] $gallos_hijos
 *
 * @package App\Models
 */
class Gallina extends Model
{
	protected $table = 'gallinas';
	public $timestamps = false;

	protected $casts = [
		'padre_id' => 'int',
		'madre_id' => 'int'
	];

	protected $fillable = [
		'padre_id',
		'madre_id',
		'placa',
		'marca_nacimiento',
		'marca_federacion',
		'color',
		'cresta',
		'fecha_nacimiento',
		'luna',
		'observaciones'
	];

	public function gallinas_imagenes()
	{
		return $this->hasMany(GallinasImagene::class);
	}

	public function gallos_hijos()
	{
		return $this->hasMany(GallosHijo::class, 'hijo_id')->where('tipo', 'Gallina');
	}

	
}
