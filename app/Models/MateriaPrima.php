<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MateriaPrima extends Model
{
    public $timestamps = false;

    protected $table = 'materia_prima';
    protected $primaryKey = 'id_materia';

    protected $fillable = [
        'codigo',
        'nombre',
        'existencia_actual',
        'stock_minimo',
        'id_unidad',
        'fecha_vencimiento',
        'estado',
        'fecha_registro',
    ];

    protected $casts = [
        'existencia_actual' => 'decimal:2',
        'stock_minimo' => 'decimal:2',
        'fecha_vencimiento' => 'date',
    ];

    public function unidadMedida(): BelongsTo
    {
        return $this->belongsTo(UnidadMedida::class, 'id_unidad', 'id_unidad');
    }

    public function movimientos(): HasMany
    {
        return $this->hasMany(MovimientoMateria::class, 'id_materia', 'id_materia');
    }
}

