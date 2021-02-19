<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Combustible extends Model
{
    //Nombre de la table
    protected $table = 'combustibles';

    //Llave primaria
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre'
    ];

    public function setNombreAttribute($value) 
    {
        $this->attributes['nombre'] = strtoupper($value);
    }

}
