<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class Data extends Model
{
    use HasFactory;
    protected $fillable = [
        'orden',
        'nombres',
        'cedula',
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

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($orden) {
            do {
                $randomCode = 'OR-' . strtoupper(Str::random(5));
            } while (self::where('orden', $randomCode)->exists());

            $orden->orden = $randomCode;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}


