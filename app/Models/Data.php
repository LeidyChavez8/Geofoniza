<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
    use HasFactory;
    protected $fillable = [
        'orden',
        'nombres',
        'direccion',
        'barrio',
        'telefono',
        'correo',
        
        'medidor',
        'lectura',
        'aforo',
        'resultado',
        'observacion_inspeccion',
        'url_foto',
        'firmaUsuario',
        'firmaTecnico',
        'ciclo',
        'id_user',
        'puntoHidraulico',
        'numeroPersonas',
        'categoria',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}


