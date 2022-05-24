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
        'id_user',
        'updated_at',
        'created_at',
    ];

    public function cartao()
    {
        return $this->hasMany(Cartao::class, 'id_quadro', 'id_quadro');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id_user', 'id_user');
    }
}

?>