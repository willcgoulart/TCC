<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class TarefaEtiqueta extends Model
{
    protected $table = 'tarefas_etiqueta';
    protected $primaryKey = 'id_tarefa_etiqueta';
    public $timestamps = false;

    protected $fillable = [
        'id_tarefa',
        'id_etiqueta',
        'updated_at',
        'created_at',
    ];

    public function Etiqueta()
    {
        return $this->hasOne(Etiqueta::class, 'id_etiqueta', 'id_etiqueta');
    }

    public function dadosTarefa()
    {
        return $this->belongsTo(Tarefa::class);
    }
}

?>