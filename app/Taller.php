<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Taller extends Model
{
    //Nombre de la table
    protected $table = 'talleres';

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

    public function vehiculos()
    {
        return $this->hasMany(Vehiculo::class, 'id_taller');
    }

}
