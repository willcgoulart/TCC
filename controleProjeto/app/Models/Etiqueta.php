<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Etiqueta extends Model
{
    protected $table = 'etiquetas';
    protected $primaryKey = 'id_etiqueta';
    public $timestamps = false;

    protected $fillable = [
        'desc_etiqueta',
        'cor_etiqueta',
        'id_etiqueta_tipo',
        'id_empresa',
        'id_user',
        'updated_at',
        'created_at',
    ];

    public function dadosUser()
    {
        return $this->belongsTo(User::class);
    }

}

?>