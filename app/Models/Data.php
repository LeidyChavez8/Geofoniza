<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
    use HasFactory;
    protected $fillable = [
        'ciclo',
        'nombre_cliente',
        'email',
        'telefono',
        'firma',
        'cuenta',
        'direccion',
        'recorrido',
        'medidor',
        'año',
        'mes',
        'periodo',
        'id_operario',
        'estado',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_operario');
    }
}


