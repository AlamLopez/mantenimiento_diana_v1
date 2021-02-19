<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    //Nombre de la table
    protected $table = 'vehiculos';

    //Llave primaria
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'codigo_comb', 'placa', 'numero_vehiculo', 'marca', 'modelo', 'anio', 'id_distribuidora', 'conductor', 'id_taller', 'periodo_mtto_kms', 'periodo_mtto_meses', 'ruta', 'propietario'
    ];

    public function setCodigoCombAttribute($value) 
    {
        $this->attributes['codigo_comb'] = strtoupper($value);
    }

    public function setPlacaAttribute($value) 
    {
        $this->attributes['placa'] = strtoupper($value);
    }

    public function setMarcaAttribute($value) 
    {
        $this->attributes['marca'] = strtoupper($value);
    }

    public function setModeloAttribute($value) 
    {
        $this->attributes['modelo'] = strtoupper($value);
    }

    public function setConductorAttribute($value) 
    {
        $this->attributes['conductor'] = strtoupper($value);
    }

    public function setRutaAttribute($value) 
    {
        $this->attributes['ruta'] = strtoupper($value);
    }

    public function distribuidora()
    {
        return $this->belongsTo(Distribuidora::class, 'id_distribuidora');
    }

    public function taller()
    {
        return $this->belongsTo(Taller::class, 'id_taller');
    }

    public function rendimientos()
    {
        return $this->hasMany(Rendimiento::class, 'id_vehiculo');
    }

    public function semaforo()
    {
        return $this->hasOne(Semaforo::class, 'id_vehiculo');
    }

    public function mantenimientos()
    {
        return $this->hasMany(Mantenimiento::class, 'id_vehiculo');
    }
}
