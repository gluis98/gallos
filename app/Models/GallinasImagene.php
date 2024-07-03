<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class GallinasImagene
 * 
 * @property int $id
 * @property int $gallina_id
 * @property string|null $imagen
 * 
 * @property Gallina $gallina
 *
 * @package App\Models
 */
class GallinasImagene extends Model
{
	protected $table = 'gallinas_imagenes';
	public $timestamps = false;

	protected $casts = [
		'gallina_id' => 'int'
	];

	protected $fillable = [
		'gallina_id',
		'imagen'
	];

	public function gallina()
	{
		return $this->belongsTo(Gallina::class);
	}
}
