<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class UserTipo extends Model
{
    protected $table = 'users_tipo';
    protected $primaryKey = 'id_user_tipo';

    public function dadosUser()
    {
        return $this->belongsTo(User::class);
    }

}

?>