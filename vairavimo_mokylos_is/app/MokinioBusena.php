<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MokinioBusena extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'mokinio_busenos';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['teorinio_egzamino_ivertinimas', 'praktinio_egzamino_ivertinimas', 'mokinys'];

    public function mokinys()
	{
		return $this->belongsTo('App\Mokiny');
	}
	
}
