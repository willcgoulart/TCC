<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class EtiquetaTipo extends Model
{
    protected $table = 'etiquetas_tipo';
    protected $primaryKey = 'etiquetas_tipo';

    public function dadosEtiqueta()
    {
        return $this->belongsTo(Etiqueta::class);
    }

}

?>