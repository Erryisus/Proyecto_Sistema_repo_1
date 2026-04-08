<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;
    protected $table = "ventas";
    protected $primaryKey = "id_venta";
    public $timestamps = true;
    protected $fillable = [
        "codigo_venta", "id_cliente", "id_usuario", "total", "fecha", "estado", "foto"
    ];

    // Note: Models for Cliente, Usuario exist as Usuario.php. VentaDetalle uses raw queries.
    // Relationships optional since controller uses DB queries.
}
