<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Empresa extends Model
{
    protected $table = 'empresas';
    protected $primaryKey = 'id_empresa';

    //protected $fillable = ['desc_empresa', 'updated_at', 'created_at'];

    public function dadosUser()
    {
        return $this->belongsTo(User::class);
    }

}

?>