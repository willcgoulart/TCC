<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class TarefaObs extends Model
{
    
    protected $table = 'tarefas_obs';
    protected $primaryKey = 'id_tarefa_obs';
    public $timestamps = false;

    protected $fillable = [
        'desc_obs',
        'id_tarefa',
        'updated_at',
        'created_at',
    ];

    public function dadosTarefa()
    {
        return $this->belongsTo(Tarefa::class);
    }
}

?>