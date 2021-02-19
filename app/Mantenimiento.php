<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mantenimiento extends Model
{
    //Nombre de la table
    protected $table = 'mantenimientos';

    //Llave primaria
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_vehiculo', 'fecha', 'kilometraje', 'nombre_rutina_anterior', 'nombre_rutina_nueva'
    ];

    public function semaforo()
    {
        return $this->hasOne(Semaforo::class, 'id_mantenimiento');
    }

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'id_vehiculo');
    }

}
