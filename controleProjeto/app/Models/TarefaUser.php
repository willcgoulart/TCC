<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class TarefaUser extends Model
{
    protected $table = 'tarefas_user';
    protected $primaryKey = 'id_tarefa_user';
    public $timestamps = false;

    protected $fillable = [
        'id_tarefa',
        'id_user',
        'updated_at',
        'created_at',
    ];

    public function User()
    {
        return $this->hasOne(User::class, 'id_user', 'id_user');
    }

    public function dadosTarefa()
    {
        return $this->belongsTo(Tarefa::class);
    }
}

?>