<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MovimientoMateria extends Model
{
    protected $table = 'movimiento_materia';
    protected $primaryKey = 'id_movimiento';

    public $timestamps = false;

    protected $fillable = [
        'id_materia',
        'id_tipo_movimiento',
        'cantidad',
        'existencia_anterior',
        'existencia_nueva',
        'razon',
        'fecha',
        'usuario',
    ];

    protected $casts = [
        'cantidad' => 'decimal:2',
        'existencia_anterior' => 'decimal:2',
        'existencia_nueva' => 'decimal:2',
        'fecha' => 'datetime',
    ];

    public function materiaPrima(): BelongsTo
    {
        return $this->belongsTo(MateriaPrima::class, 'id_materia', 'id_materia');
    }

    public function tipoMovimiento(): BelongsTo
    {
        return $this->belongsTo(TipoMovimiento::class, 'id_tipo_movimiento', 'id_tipo_movimiento');
    }
}

