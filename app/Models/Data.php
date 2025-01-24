<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
    use HasFactory;
    protected $fillable = [
        'contrato',
        'producto',
        'nombres',
        'calificacion',
        'categoria',
        'direccion',
        'ubicacion',
        'medidor',
        'orden',
        'lectura_anterior',
        'fecha_lectura_anterior',
        'observacion_lectura_anterior',
        'ciclo',
        'recorrido',
        'lectura',
        'observacion_inspeccion',
        'url_foto',
        'firma',
        'id_user',
        'estado',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}


