<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table = 'venta';

    protected $primaryKey = 'idventa';

    public $timestamps = false;

    protected $fillable = [
        'idventa', 'idconf', 'tipocomp', 'idcliente', 'idusuario', 'idserie', 'fecha_emision',
        'op_gravadas', 'op_exoneradas', 'op_inafectas', 'igv', 'total', 'estado', 'feestado', 'numope',
        'formadepago', 'efectivo', 'vuelto', 'tire', 'nombrexml', 'femensajesunat',
    ];

    protected $casts = [
        'fecha_emision' => 'datetime',
        'total' => 'decimal:2',
    ];
}
