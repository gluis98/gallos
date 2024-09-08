<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class GallosImagene
 * 
 * @property int $id
 * @property int $gallo_id
 * @property string|null $imagen
 * 
 * @property Gallo $gallo
 *
 * @package App\Models
 */
class GallosImagene extends Model
{
	protected $table = 'gallos_imagenes';
	public $timestamps = false;

	protected $casts = [
		'gallo_id' => 'int'
	];

	protected $fillable = [
		'gallo_id',
		'imagen'
	];

	public function gallo()
	{
		return $this->belongsTo(Gallo::class);
	}
}
