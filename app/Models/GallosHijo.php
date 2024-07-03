<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class GallosHijo
 * 
 * @property int $id
 * @property int|null $padre_id
 * @property int|null $madre_id
 * @property int|null $hijo_id
 * 
 * @property Gallo|null $gallo
 * @property Gallina|null $gallina
 *
 * @package App\Models
 */
class GallosHijo extends Model
{
	protected $table = 'gallos_hijos';
	public $timestamps = false;

	protected $casts = [
		'padre_id' => 'int',
		'madre_id' => 'int',
		'hijo_id' => 'int'
	];

	protected $fillable = [
		'padre_id',
		'madre_id',
		'hijo_id'
	];

	public function gallo()
	{
		return $this->belongsTo(Gallo::class, 'hijo_id');
	}

	public function gallina()
	{
		return $this->belongsTo(Gallina::class, 'madre_id');
	}
}
