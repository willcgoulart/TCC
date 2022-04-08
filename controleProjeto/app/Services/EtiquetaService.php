<?php 
namespace  App\Services;

use App\Models\Etiqueta;
use Illuminate\Support\Facades\DB;

class EtiquetaService{

    public function criarEtiqueta(
        string $desc_etiqueta, 
        string $cor_etiqueta, 
        int $id_etiqueta_tipo, 
        int $id_empresa, 
        int $id_user
        ): Etiqueta
    {
        DB::beginTransaction();
            $etiqueta = Etiqueta::create([
                'desc_etiqueta' => $desc_etiqueta, 
                'cor_etiqueta' => $cor_etiqueta, 
                'id_etiqueta_tipo' => $id_etiqueta_tipo, 
                'id_empresa' => $id_empresa, 
                'id_user' => $id_user
            ]);
        DB::commit();

        return $etiqueta;
    }

}

?>