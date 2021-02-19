<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Distribuidora extends Model
{
    //Nombre de la table
    protected $table = 'distribuidoras';

    //Llave primaria
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre', 'pais'
    ];

    public function setNombreAttribute($value) 
    {
        $this->attributes['nombre'] = strtoupper($value);
    }

    public function setPaisAttribute($value) 
    {
        $this->attributes['pais'] = strtoupper($value);
    }
}
