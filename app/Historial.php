<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Historial extends Model
{
    
    //Nombre de la table
    protected $table = 'historial';

    //Llave primaria
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre_accion', 'descripcion', 'id_user'
    ];

    public function setNombreAccionAttribute($value) 
    {
        $this->attributes['nombre_accion'] = strtoupper($value);
    }

}
