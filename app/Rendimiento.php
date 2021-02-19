<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rendimiento extends Model
{
    //Nombre de la table
    protected $table = 'rendimientos';

    //Llave primaria
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fecha', 'id_vehiculo', 'id_data', 'id_combustible', 'kilometraje', 'cantidad_galones', 'valor', 'recorrido', 'rendimiento', 'status'
    ];

    public function vehiculo()
    {
        return $this->belongTo(Vehiculo::class, 'id_vehiculo');
    }

}
