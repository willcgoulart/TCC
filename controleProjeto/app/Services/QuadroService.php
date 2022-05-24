<?php 
namespace  App\Services;

use App\Models\Quadro;
use App\Models\Cartao;
use App\Models\Tarefa;
use App\Models\TarefaUser;
use App\Models\TarefaEtiqueta;
use App\Models\TarefaObs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuadroService{

    public function criarQuadro($dados)
    {
        DB::beginTransaction();
            $quadro = Quadro::create([
                'desc_quadro' => $dados['desc_quadro'],
                'id_user' => Auth::user()->id_user
               
            ]);
        DB::commit();

        foreach( $dados['dados_cartoes'] as $key => $dado ) 
        {
            if($key>0)
            {
                DB::beginTransaction();
                    $cartao = Cartao::create([
                        'desc_cartao' => $dado['desc_cartao'],
                        'id_quadro' => $quadro->id_quadro
                    ]);
                DB::commit();

                foreach( $dado['tarefas'] as $key => $tarefa ) 
                {
                    DB::beginTransaction();
                        $tarefaId = Tarefa::create([
                            'desc_tarefa' => $tarefa['desc_tarefa'],
                            'id_cartao' => $cartao->id_cartao,
                            'status' => $tarefa['status'],
                            'data_entrega' => $tarefa['data_entrega'] 
                        ]);
                    DB::commit();

                    if( !empty( $tarefa['obs'] ) )
                    {
                        DB::beginTransaction();
                            TarefaObs::create([
                                'desc_obs' => $tarefa['obs'],
                                'id_tarefa' => $tarefaId->id_tarefa 
                            ]);
                        DB::commit();
                    }
                    
                    $users = explode( "|",$tarefa['user'] );
                    foreach( $users as $key => $user ) 
                    {
                        if( !empty($user) )
                        {
                            DB::beginTransaction();
                                TarefaUser::create([
                                    'id_user' => $user,
                                    'id_tarefa' => $tarefaId->id_tarefa 
                                ]);
                            DB::commit();
                        }
                    }

                    $etiquetas = explode( "|",$tarefa['etiqueta'] );
                    foreach( $etiquetas as $key => $etiqueta ) 
                    {
                        if( !empty($etiqueta) )
                        {
                            DB::beginTransaction();
                                TarefaEtiqueta::create([
                                    'id_etiqueta' => $etiqueta,
                                    'id_tarefa' => $tarefaId->id_tarefa
                                ]);
                            DB::commit();
                        }
                    }
                }
            }
        }
        return $dados;
    }

    public function deletarQuadro(int $idQuadro): string
    {
		DB::transaction(function () use ($idQuadro, &$descQuadro) {
			$quadro = Quadro::find($idQuadro);
			$descQuadro = $quadro->desc_quadro;
			
			$this->removerCartoes($quadro);
			$quadro->delete();
		});
		return $descQuadro;
	}

    private function removerCartoes($quadro):void
    {
		$quadro->cartao->each(function (Cartao $cartao)
        {
			$this->removerTarefas($cartao);
			$cartao->delete();
		});
	}
    
	private function removerTarefas(Cartao $cartao):void
    {
        $cartao->tarefa->each(function (Tarefa $tarefa)
        {
            $this->removerTarefaEtiqueta($tarefa);
            $this->removerTarefaUser($tarefa);
            $this->removerTarefaObs($tarefa);
			$tarefa->delete();
		});
	}

    private function removerTarefaEtiqueta($tarefa):void
    {
		$tarefa->tarefaEtiqueta->each(function (TarefaEtiqueta $tarefaEtiqueta)
        {
			$tarefaEtiqueta->delete();
		});
	}

    private function removerTarefaUser($tarefa):void
    {
		$tarefa->tarefaUser->each(function (TarefaUser $tarefaUser)
        {
			$tarefaUser->delete();
		});
	}

    private function removerTarefaObs($tarefa):void
    {
        $tarefa->tarefaObs->each(function (TarefaObs $tarefaObs)
        {
			$tarefaObs->delete();
		});
	}


}

?>