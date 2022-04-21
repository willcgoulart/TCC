<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Quadro extends Model
{
    protected $table = 'quadros';
    protected $primaryKey = 'id_quadro';
    public $timestamps = false;

    protected $fillable = [
        'desc_quadro',
        'status',
        'updated_at',
        'created_at',
    ];

    public function cartao()
    {
        return $this->hasMany(Cartao::class, 'id_quadro', 'id_quadro');
    }
}

?>