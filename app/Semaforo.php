<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Semaforo extends Model
{
    //Nombre de la table
    protected $table = 'semaforos';

    //Llave primaria
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_vehiculo', 'id_mantenimiento', 'diferencia_kms', 'diferencia_kms_color', 'fecha_prox_manto_kms', 'diferencia_dias', 'diferencia_dias_color', 'fecha_prox_manto_tiempo'
    ];

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'id_vehiculo');
    }

    public function mantenimiento()
    {
        return $this->belongsTo(Mantenimiento::class, 'id_mantenimiento');
    }
}
