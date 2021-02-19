<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CostoRutina extends Model
{
    //Nombre de la table
    protected $table = 'costorutinas';

    //Llave primaria
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'modelo', 'rutina', 'costo', 'id_user', 'id_distribuidora'
    ];

}
