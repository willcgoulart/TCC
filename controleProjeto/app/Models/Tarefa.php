<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Tarefa extends Model
{
    protected $table = 'tarefas';
    protected $primaryKey = 'id_tarefa';
    public $timestamps = false;

    protected $fillable = [
        'desc_tarefa',
        'id_cartao',
        'status',
        'data_entrega',
        'updated_at',
        'created_at',
    ];

    public function tarefaEtiqueta()
    {
        return $this->hasMany(TarefaEtiqueta::class, 'id_tarefa', 'id_tarefa');
    }

    public function tarefaUser()
    {
        return $this->hasMany(TarefaUser::class, 'id_tarefa', 'id_tarefa');
    }
    
    public function tarefaObs()
    {
        return $this->hasMany(TarefaObs::class, 'id_tarefa', 'id_tarefa');
    }

    public function dadosCartao()
    {
        return $this->belongsTo(Cartao::class);
    }
}

?>