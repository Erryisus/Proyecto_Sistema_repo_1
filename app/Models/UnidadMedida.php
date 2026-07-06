<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnidadMedida extends Model
{
    protected $table = 'unidad_medida';
    protected $primaryKey = 'id_unidad';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'abreviatura',
    ];

    public function materiasPrimas(): HasMany
    {
        return $this->hasMany(MateriaPrima::class, 'id_unidad', 'id_unidad');
    }
}

