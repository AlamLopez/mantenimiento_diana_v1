<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    //Nombre de la table
    protected $table = 'roles';

    //Llave primaria
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre', 'descripcion'
    ];

    public function setNombreAttribute($value) 
    {
        $this->attributes['nombre'] = strtoupper($value);
    }

    public function setDescripcionAttribute($value) 
    {
        $this->attributes['descripcion'] = strtoupper($value);
    }
}
