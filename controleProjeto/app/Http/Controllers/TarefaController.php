<?php

namespace App\Http\Controllers;

use App\Models\Tarefa;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TarefaController extends Controller
{
    public function listaPendente(Request $request)
    {
        DB::statement("SET SQL_MODE=''");//this is the trick use it just before your query
        $tarefasSemUser = DB::table('quadros')
            ->select('quadros.id_quadro','quadros.desc_quadro',
                'cartoes.id_cartao','cartoes.desc_cartao',
                DB::raw('GROUP_CONCAT(tarefas.id_tarefa ORDER BY tarefas.data_entrega ASC SEPARATOR "|") AS id_tarefa'),
                DB::raw('GROUP_CONCAT(tarefas.desc_tarefa ORDER BY tarefas.data_entrega ASC SEPARATOR "|") AS desc_tarefa'),
                DB::raw('GROUP_CONCAT( IF(tarefas.data_entrega IS NULL, "", DATE_FORMAT(tarefas.data_entrega,"%d/%m/%Y") ) 
                    ORDER BY tarefas.data_entrega ASC SEPARATOR "|") AS data_entrega'),
                DB::raw('GROUP_CONCAT(tarefas.status ORDER BY tarefas.data_entrega ASC SEPARATOR "|") AS status')
            )->join('cartoes', 'cartoes.id_quadro', '=', 'quadros.id_quadro')
            ->join('tarefas', 'tarefas.id_cartao', '=', 'cartoes.id_cartao')
            ->where( [ 
                ['quadros.id_user','=',Auth::user()->id_user],
                ['tarefas.status','=','PE']
            ])->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('tarefas_user')
                      ->whereColumn('tarefas_user.id_tarefa', 'tarefas.id_tarefa');
        })->groupBy('quadros.id_quadro','cartoes.id_cartao');

        $todasTarefas = DB::table('tarefas')
            ->select('quadros.id_quadro','quadros.desc_quadro',
                'cartoes.id_cartao','cartoes.desc_cartao',
                DB::raw('GROUP_CONCAT(tarefas.id_tarefa ORDER BY tarefas.data_entrega ASC SEPARATOR "|") AS id_tarefa'),
                DB::raw('GROUP_CONCAT(tarefas.desc_tarefa ORDER BY tarefas.data_entrega ASC SEPARATOR "|") AS desc_tarefa'),
                DB::raw('GROUP_CONCAT( IF(tarefas.data_entrega IS NULL, "", DATE_FORMAT(tarefas.data_entrega,"%d/%m/%Y") ) 
                    ORDER BY tarefas.data_entrega ASC SEPARATOR "|") AS data_entrega'),
                DB::raw('GROUP_CONCAT(tarefas.status ORDER BY tarefas.data_entrega ASC SEPARATOR "|") AS status')
            )->join('tarefas_user', 'tarefas_user.id_tarefa', '=', 'tarefas.id_tarefa')
            ->join('cartoes', 'cartoes.id_cartao', '=', 'tarefas.id_cartao')
            ->join('quadros', 'quadros.id_quadro', '=', 'cartoes.id_quadro')
            ->where( [ 
                ['tarefas_user.id_user','=',Auth::user()->id_user],
                ['tarefas.status','=','PE']
            ])->groupBy('quadros.id_quadro','cartoes.id_cartao')
        ->union($tarefasSemUser)->get();

        $mensagem = $request->session()->get('mensagem');
        return view('tarefa.lista', compact('todasTarefas','mensagem'));
    }

    public function listaAtraso(Request $request)
    {
        DB::statement("SET SQL_MODE=''");//this is the trick use it just before your query
        $tarefasSemUser = DB::table('quadros')
            ->select('quadros.id_quadro','quadros.desc_quadro',
                'cartoes.id_cartao','cartoes.desc_cartao',
                DB::raw('GROUP_CONCAT(tarefas.id_tarefa ORDER BY tarefas.data_entrega ASC SEPARATOR "|") AS id_tarefa'),
                DB::raw('GROUP_CONCAT(tarefas.desc_tarefa ORDER BY tarefas.data_entrega ASC SEPARATOR "|") AS desc_tarefa'),
                DB::raw('GROUP_CONCAT( IF(tarefas.data_entrega IS NULL, "", DATE_FORMAT(tarefas.data_entrega,"%d/%m/%Y") ) 
                    ORDER BY tarefas.data_entrega ASC SEPARATOR "|") AS data_entrega'),
                DB::raw('GROUP_CONCAT(tarefas.status ORDER BY tarefas.data_entrega ASC SEPARATOR "|") AS status')
            )->join('cartoes', 'cartoes.id_quadro', '=', 'quadros.id_quadro')
            ->join('tarefas', 'tarefas.id_cartao', '=', 'cartoes.id_cartao')
            ->where( [ 
                ['quadros.id_user','=',Auth::user()->id_user],
                ['tarefas.data_entrega','<=',date('Y-m-d')],
                ['tarefas.status','<>','CO']
            ])->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('tarefas_user')
                      ->whereColumn('tarefas_user.id_tarefa', 'tarefas.id_tarefa');
        })->groupBy('quadros.id_quadro','cartoes.id_cartao');

        $todasTarefas = DB::table('tarefas')
            ->select('quadros.id_quadro','quadros.desc_quadro',
                'cartoes.id_cartao','cartoes.desc_cartao',
                DB::raw('GROUP_CONCAT(tarefas.id_tarefa ORDER BY tarefas.data_entrega ASC SEPARATOR "|") AS id_tarefa'),
                DB::raw('GROUP_CONCAT(tarefas.desc_tarefa ORDER BY tarefas.data_entrega ASC SEPARATOR "|") AS desc_tarefa'),
                DB::raw('GROUP_CONCAT( IF(tarefas.data_entrega IS NULL, "", DATE_FORMAT(tarefas.data_entrega,"%d/%m/%Y") ) 
                    ORDER BY tarefas.data_entrega ASC SEPARATOR "|") AS data_entrega'),
                DB::raw('GROUP_CONCAT(tarefas.status ORDER BY tarefas.data_entrega ASC SEPARATOR "|") AS status')
            )->join('tarefas_user', 'tarefas_user.id_tarefa', '=', 'tarefas.id_tarefa')
            ->join('cartoes', 'cartoes.id_cartao', '=', 'tarefas.id_cartao')
            ->join('quadros', 'quadros.id_quadro', '=', 'cartoes.id_quadro')
            ->where( [ 
                ['tarefas_user.id_user','=',Auth::user()->id_user],
                ['tarefas.data_entrega','<=',date('Y-m-d')],
                ['tarefas.status','<>','CO']
            ])->groupBy('quadros.id_quadro','cartoes.id_cartao')
        ->union($tarefasSemUser)->get();

        $mensagem = $request->session()->get('mensagem');
        return view('tarefa.lista', compact('todasTarefas','mensagem'));
    }

    public function buscaDados(Request $request)
    {
        $tarefa = Tarefa::find( $request->id_tarefa );

        $tarefaEtiqueta = DB::table('etiquetas')
            ->select('etiquetas.desc_etiqueta','etiquetas.cor_etiqueta')
            ->join('tarefas_etiqueta', 'tarefas_etiqueta.id_etiqueta', '=', 'etiquetas.id_etiqueta')
            ->where( [ 
                ['tarefas_etiqueta.id_tarefa','=',$request->id_tarefa]
            ])
        ->orderBy('etiquetas.desc_etiqueta', 'asc')->get();

        $tarefaUser = DB::table('users')
            ->select('users.name')
            ->join('tarefas_user', 'tarefas_user.id_user', '=', 'users.id_user')
            ->where( [ 
                ['tarefas_user.id_tarefa','=',$request->id_tarefa]
            ])
        ->orderBy('users.name', 'asc')->get();

        $tarefaObs = DB::table('tarefas_obs')
            ->select('tarefas_obs.desc_obs')
            ->where( [ 
                ['tarefas_obs.id_tarefa','=',$request->id_tarefa]
            ])
        ->orderBy('tarefas_obs.id_tarefa_obs', 'asc')->get();

        $result['success'] = true;
        $result['tarefa'] = $tarefa;
        $result['tarefa_etiqueta'] = $tarefaEtiqueta;
        $result['tarefa_user'] = $tarefaUser;
        $result['tarefa_obs'] = $tarefaObs;
        echo json_encode($result);
    }

    public function lista($cartaoId)
    {
        DB::statement("SET SQL_MODE=''");//this is the trick use it just before your query
        $todasTarefasPe = DB::table('quadros')
            ->select('quadros.id_quadro','quadros.desc_quadro',
                'cartoes.id_cartao','cartoes.desc_cartao',
                DB::raw('GROUP_CONCAT(tarefas.id_tarefa ORDER BY tarefas.data_entrega ASC SEPARATOR "|") AS id_tarefa'),
                DB::raw('GROUP_CONCAT(tarefas.desc_tarefa ORDER BY tarefas.data_entrega ASC SEPARATOR "|") AS desc_tarefa'),
                DB::raw('GROUP_CONCAT( IF(tarefas.data_entrega IS NULL, "", DATE_FORMAT(tarefas.data_entrega,"%d/%m/%Y") ) 
                    ORDER BY tarefas.data_entrega ASC SEPARATOR "|") AS data_entrega'),
                DB::raw('GROUP_CONCAT(tarefas.status ORDER BY tarefas.data_entrega ASC SEPARATOR "|") AS status')
            )->join('cartoes', 'cartoes.id_quadro', '=', 'quadros.id_quadro')
            ->join('tarefas', 'tarefas.id_cartao', '=', 'cartoes.id_cartao')
            ->where( [ 
                ['cartoes.id_cartao','=',$cartaoId],
                ['tarefas.status','=','PE']
            ])
        ->get();

        $todasTarefasFa = DB::table('quadros')
            ->select('quadros.id_quadro','quadros.desc_quadro',
                'cartoes.id_cartao','cartoes.desc_cartao',
                DB::raw('GROUP_CONCAT(tarefas.id_tarefa ORDER BY tarefas.data_entrega ASC SEPARATOR "|") AS id_tarefa'),
                DB::raw('GROUP_CONCAT(tarefas.desc_tarefa ORDER BY tarefas.data_entrega ASC SEPARATOR "|") AS desc_tarefa'),
                DB::raw('GROUP_CONCAT( IF(tarefas.data_entrega IS NULL, "", DATE_FORMAT(tarefas.data_entrega,"%d/%m/%Y") ) 
                    ORDER BY tarefas.data_entrega ASC SEPARATOR "|") AS data_entrega'),
                DB::raw('GROUP_CONCAT(tarefas.status ORDER BY tarefas.data_entrega ASC SEPARATOR "|") AS status')
            )->join('cartoes', 'cartoes.id_quadro', '=', 'quadros.id_quadro')
            ->join('tarefas', 'tarefas.id_cartao', '=', 'cartoes.id_cartao')
            ->where( [ 
                ['cartoes.id_cartao','=',$cartaoId],
                ['tarefas.status','=','FA']
            ])
        ->get();

        $todasTarefasPa = DB::table('quadros')
            ->select('quadros.id_quadro','quadros.desc_quadro',
                'cartoes.id_cartao','cartoes.desc_cartao',
                DB::raw('GROUP_CONCAT(tarefas.id_tarefa ORDER BY tarefas.data_entrega ASC SEPARATOR "|") AS id_tarefa'),
                DB::raw('GROUP_CONCAT(tarefas.desc_tarefa ORDER BY tarefas.data_entrega ASC SEPARATOR "|") AS desc_tarefa'),
                DB::raw('GROUP_CONCAT( IF(tarefas.data_entrega IS NULL, "", DATE_FORMAT(tarefas.data_entrega,"%d/%m/%Y") ) 
                    ORDER BY tarefas.data_entrega ASC SEPARATOR "|") AS data_entrega'),
                DB::raw('GROUP_CONCAT(tarefas.status ORDER BY tarefas.data_entrega ASC SEPARATOR "|") AS status')
            )->join('cartoes', 'cartoes.id_quadro', '=', 'quadros.id_quadro')
            ->join('tarefas', 'tarefas.id_cartao', '=', 'cartoes.id_cartao')
            ->where( [ 
                ['cartoes.id_cartao','=',$cartaoId],
                ['tarefas.status','=','PA']
            ])
        ->get();

        $todasTarefasCo = DB::table('quadros')
            ->select('quadros.id_quadro','quadros.desc_quadro',
                'cartoes.id_cartao','cartoes.desc_cartao',
                DB::raw('GROUP_CONCAT(tarefas.id_tarefa ORDER BY tarefas.data_entrega ASC SEPARATOR "|") AS id_tarefa'),
                DB::raw('GROUP_CONCAT(tarefas.desc_tarefa ORDER BY tarefas.data_entrega ASC SEPARATOR "|") AS desc_tarefa'),
                DB::raw('GROUP_CONCAT( IF(tarefas.data_entrega IS NULL, "", DATE_FORMAT(tarefas.data_entrega,"%d/%m/%Y") ) 
                    ORDER BY tarefas.data_entrega ASC SEPARATOR "|") AS data_entrega'),
                DB::raw('GROUP_CONCAT(tarefas.status ORDER BY tarefas.data_entrega ASC SEPARATOR "|") AS status')
            )->join('cartoes', 'cartoes.id_quadro', '=', 'quadros.id_quadro')
            ->join('tarefas', 'tarefas.id_cartao', '=', 'cartoes.id_cartao')
            ->where( [ 
                ['cartoes.id_cartao','=',$cartaoId],
                ['tarefas.status','=','CO']
            ])
        ->get();

        return view('tarefa.listaKanban', compact('todasTarefasPe','todasTarefasFa','todasTarefasPa','todasTarefasCo'));
    }

    public function listaUser($idUser, Request $request)
    {
        DB::statement("SET SQL_MODE=''");//this is the trick use it just before your query
        $tarefasSemUser = DB::table('quadros')
            ->select('quadros.id_quadro','quadros.desc_quadro',
                'cartoes.id_cartao','cartoes.desc_cartao',
                DB::raw('GROUP_CONCAT(tarefas.id_tarefa ORDER BY tarefas.data_entrega ASC SEPARATOR "|") AS id_tarefa'),
                DB::raw('GROUP_CONCAT(tarefas.desc_tarefa ORDER BY tarefas.data_entrega ASC SEPARATOR "|") AS desc_tarefa'),
                DB::raw('GROUP_CONCAT( IF(tarefas.data_entrega IS NULL, "", DATE_FORMAT(tarefas.data_entrega,"%d/%m/%Y") ) 
                    ORDER BY tarefas.data_entrega ASC SEPARATOR "|") AS data_entrega'),
                DB::raw('GROUP_CONCAT(tarefas.status ORDER BY tarefas.data_entrega ASC SEPARATOR "|") AS status')
            )->join('cartoes', 'cartoes.id_quadro', '=', 'quadros.id_quadro')
            ->join('tarefas', 'tarefas.id_cartao', '=', 'cartoes.id_cartao')
            ->where( [ 
                ['quadros.id_user','=',$idUser],
                ['tarefas.status','=','PE']
            ])->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('tarefas_user')
                      ->whereColumn('tarefas_user.id_tarefa', 'tarefas.id_tarefa');
        })->groupBy('quadros.id_quadro','cartoes.id_cartao');

        $todasTarefas = DB::table('tarefas')
            ->select('quadros.id_quadro','quadros.desc_quadro',
                'cartoes.id_cartao','cartoes.desc_cartao',
                DB::raw('GROUP_CONCAT(tarefas.id_tarefa ORDER BY tarefas.data_entrega ASC SEPARATOR "|") AS id_tarefa'),
                DB::raw('GROUP_CONCAT(tarefas.desc_tarefa ORDER BY tarefas.data_entrega ASC SEPARATOR "|") AS desc_tarefa'),
                DB::raw('GROUP_CONCAT( IF(tarefas.data_entrega IS NULL, "", DATE_FORMAT(tarefas.data_entrega,"%d/%m/%Y") ) 
                    ORDER BY tarefas.data_entrega ASC SEPARATOR "|") AS data_entrega'),
                DB::raw('GROUP_CONCAT(tarefas.status ORDER BY tarefas.data_entrega ASC SEPARATOR "|") AS status')
            )->join('tarefas_user', 'tarefas_user.id_tarefa', '=', 'tarefas.id_tarefa')
            ->join('cartoes', 'cartoes.id_cartao', '=', 'tarefas.id_cartao')
            ->join('quadros', 'quadros.id_quadro', '=', 'cartoes.id_quadro')
            ->where( [ 
                ['tarefas_user.id_user','=',$idUser],
                ['tarefas.status','=','PE']
            ])->groupBy('quadros.id_quadro','cartoes.id_cartao')
        ->union($tarefasSemUser)->get();

        $mensagem = $request->session()->get('mensagem');
        return view('tarefa.lista', compact('todasTarefas','mensagem'));
    }

    public function listaPendenteAdm(Request $request)
    {
        DB::statement("SET SQL_MODE=''");//this is the trick use it just before your query
        $todasTarefas = DB::table('quadros')
            ->select('quadros.id_quadro','quadros.desc_quadro',
                'cartoes.id_cartao','cartoes.desc_cartao',
                DB::raw('GROUP_CONCAT(tarefas.id_tarefa ORDER BY tarefas.data_entrega ASC SEPARATOR "|") AS id_tarefa'),
                DB::raw('GROUP_CONCAT(tarefas.desc_tarefa ORDER BY tarefas.data_entrega ASC SEPARATOR "|") AS desc_tarefa'),
                DB::raw('GROUP_CONCAT( IF(tarefas.data_entrega IS NULL, "", DATE_FORMAT(tarefas.data_entrega,"%d/%m/%Y") ) 
                    ORDER BY tarefas.data_entrega ASC SEPARATOR "|") AS data_entrega'),
                DB::raw('GROUP_CONCAT(tarefas.status ORDER BY tarefas.data_entrega ASC SEPARATOR "|") AS status')
            )->join('cartoes', 'cartoes.id_quadro', '=', 'quadros.id_quadro')
            ->join('tarefas', 'tarefas.id_cartao', '=', 'cartoes.id_cartao')
            ->where( [ 
                ['tarefas.status','=','PE']
            ])
        ->groupBy('quadros.id_quadro','cartoes.id_cartao')->get();

        $mensagem = $request->session()->get('mensagem');
        return view('tarefa.lista', compact('todasTarefas','mensagem'));
    }

    public function listaAtrasoAdm(Request $request)
    {
        DB::statement("SET SQL_MODE=''");//this is the trick use it just before your query
        $todasTarefas = DB::table('quadros')
            ->select('quadros.id_quadro','quadros.desc_quadro',
                'cartoes.id_cartao','cartoes.desc_cartao',
                DB::raw('GROUP_CONCAT(tarefas.id_tarefa ORDER BY tarefas.data_entrega ASC SEPARATOR "|") AS id_tarefa'),
                DB::raw('GROUP_CONCAT(tarefas.desc_tarefa ORDER BY tarefas.data_entrega ASC SEPARATOR "|") AS desc_tarefa'),
                DB::raw('GROUP_CONCAT( IF(tarefas.data_entrega IS NULL, "", DATE_FORMAT(tarefas.data_entrega,"%d/%m/%Y") ) 
                    ORDER BY tarefas.data_entrega ASC SEPARATOR "|") AS data_entrega'),
                DB::raw('GROUP_CONCAT(tarefas.status ORDER BY tarefas.data_entrega ASC SEPARATOR "|") AS status')
            )->join('cartoes', 'cartoes.id_quadro', '=', 'quadros.id_quadro')
            ->join('tarefas', 'tarefas.id_cartao', '=', 'cartoes.id_cartao')
            ->where( [ ['tarefas.data_entrega','<=',date('Y-m-d')],
                ['tarefas.status','<>','CO']
            ])
        ->groupBy('quadros.id_quadro','cartoes.id_cartao')->get();

        $mensagem = $request->session()->get('mensagem');
        return view('tarefa.lista', compact('todasTarefas','mensagem'));
    }

    public function listaDemandasUser(Request $request)
    {
        DB::statement("SET SQL_MODE=''");//this is the trick use it just before your query
        $totaltarefasUserAdm = DB::table('tarefas')
            ->select('tarefas_user.id_user','users.name',
                DB::raw('count(tarefas.id_tarefa) AS total')
            )->join('tarefas_user', 'tarefas_user.id_tarefa', '=', 'tarefas.id_tarefa')
            ->join('users', 'users.id_user', '=', 'tarefas_user.id_user')
            ->where( [ 
                ['users.id_empresa','=',Auth::user()->id_empresa],
                ['tarefas.status','=','PE']
        ])->groupBy('tarefas_user.id_user');
        $totaltarefasAdm = DB::table('quadros')
            ->select('quadros.id_user','users.name',
                DB::raw('count(tarefas.id_tarefa) AS total')
            )->join('cartoes', 'cartoes.id_quadro', '=', 'quadros.id_quadro')
            ->join('tarefas', 'tarefas.id_cartao', '=', 'cartoes.id_cartao')
            ->join('users', 'users.id_user', '=', 'quadros.id_user')
            ->where( [ 
                ['users.id_empresa','=',Auth::user()->id_empresa],
                ['tarefas.status','=','PE']
            ] )
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('tarefas_user')
                      ->whereColumn('tarefas_user.id_tarefa', 'tarefas.id_tarefa');
            })->union($totaltarefasUserAdm)
        ->groupBy('quadros.id_user')->get();

        ### Agrupa todos as tarefas por user
        foreach ($totaltarefasAdm as $key => $value) 
        {
           if( isset($totaltarefasAdmArray[$value->id_user]) )
            {
                $totaltarefasAdmArray[$value->id_user] = $totaltarefasAdmArray[$value->id_user]+$value->total;
            }
            else
            {
                $totaltarefasAdmArray[$value->id_user] = $value->total;
            }
        }

        ### Ajustar dados
        foreach ($totaltarefasAdm as $key => $value) 
        {
            if( $totaltarefasAdmArray[$value->id_user]>9 )
            {
                $dados[$value->id_user] = array(
                    'id' => $value->id_user, 
                    'name' => $value->name, 
                    'total' => $totaltarefasAdmArray[$value->id_user]
                );
            }
        }

        $mensagem = $request->session()->get('mensagem');
        return view('tarefa.listaUsers', compact('dados','mensagem'));
    }

    public function editarSalvar(Request $request)
    {
        $tarefa = Tarefa::find($request->id_tarefa);
        $tarefa->status = $request->status;
        $tarefa->save();

        $result['success'] = true;
        echo json_encode($result);
    }

}
