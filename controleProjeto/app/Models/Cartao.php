<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Cartao extends Model
{
    protected $table = 'cartoes';
    protected $primaryKey = 'id_cartao';
    public $timestamps = false;

    protected $fillable = [
        'desc_cartao',
        'id_quadro',
        'updated_at',
        'created_at',
    ];

    public function tarefa()
    {
        return $this->hasMany(Tarefa::class, 'id_cartao', 'id_cartao');
    }

    public function dadosQuadro()
    {
        return $this->belongsTo(Quadro::class);
    }
}

?>