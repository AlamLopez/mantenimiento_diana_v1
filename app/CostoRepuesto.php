<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CostoRepuesto extends Model
{
    //Nombre de la table
    protected $table = 'costorepuestos';

    //Llave primaria
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'modelo', 'rutinas', 'material', 'repuesto', 'descripcion', 'cantidad', 'costo', 'id_user', 'id_distribuidora'
    ];

    public function setMaterialAttribute($value) 
    {
        $this->attributes['material'] = strtoupper($value);
    }

    public function setRepuestoAttribute($value) 
    {
        $this->attributes['repuesto'] = strtoupper($value);
    }

    public function setDescripcionAttribute($value) 
    {
        $this->attributes['descripcion'] = strtoupper($value);
    }

}
