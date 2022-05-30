<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Etiqueta;
use App\Models\Quadro;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Services\QuadroService;

class AnaliseController extends Controller
{    
    public function index(Request $request)
    {   
        $todosQuadros = Quadro::query()->orderBy('desc_quadro')->get(); 
        
        DB::statement("SET SQL_MODE=''");//this is the trick use it just before your query
        $quadroUser = DB::table('tarefas_user')
            ->select('quadros.id_quadro','quadros.desc_quadro')
            ->join('tarefas', 'tarefas.id_tarefa', '=', 'tarefas_user.id_tarefa')
            ->join('cartoes', 'cartoes.id_cartao', '=', 'tarefas.id_cartao')
            ->join('quadros', 'quadros.id_quadro', '=', 'cartoes.id_quadro')
            ->where( [ 
                ['tarefas_user.id_user','=',Auth::user()->id_user]
        ])->groupBy('quadros.id_quadro');
        $totalQuadrosUser = DB::table('quadros')
            ->select('quadros.id_quadro','quadros.desc_quadro')
            ->where( [ 
                ['quadros.id_user','=',Auth::user()->id_user]
        ])->union($quadroUser)->get();
    
        $mensagem = $request->session()->get('mensagem');
        return view('analise.index', compact('todosQuadros','totalQuadrosUser','mensagem'));
    }

    public function todasTarefas(Request $request)
    {
        $totalTarefasPendente = DB::table('tarefas')
            ->select('tarefas.id_tarefa')
            ->join('cartoes', 'cartoes.id_cartao', '=', 'tarefas.id_cartao')
            ->join('quadros', 'quadros.id_quadro', '=', 'cartoes.id_quadro')
            ->join('users', 'users.id_user', '=', 'quadros.id_user')
            ->where( [ 
                ['users.id_user','=',Auth::user()->id_user],
                ['users.id_empresa','=',Auth::user()->id_empresa],
                ['tarefas.status','=','PE']
        ])->get();

        $totalTarefasFazendo = DB::table('tarefas')
            ->select('tarefas.id_tarefa')
            ->join('cartoes', 'cartoes.id_cartao', '=', 'tarefas.id_cartao')
            ->join('quadros', 'quadros.id_quadro', '=', 'cartoes.id_quadro')
            ->join('users', 'users.id_user', '=', 'quadros.id_user')
            ->where( [ 
                ['users.id_user','=',Auth::user()->id_user],
                ['users.id_empresa','=',Auth::user()->id_empresa],
                ['tarefas.status','=','FA']
        ])->get();

        $totalTarefasParado = DB::table('tarefas')
            ->select('tarefas.id_tarefa')
            ->join('cartoes', 'cartoes.id_cartao', '=', 'tarefas.id_cartao')
            ->join('quadros', 'quadros.id_quadro', '=', 'cartoes.id_quadro')
            ->join('users', 'users.id_user', '=', 'quadros.id_user')
            ->where( [ 
                ['users.id_user','=',Auth::user()->id_user],
                ['users.id_empresa','=',Auth::user()->id_empresa],
                ['tarefas.status','=','PA']
        ])->get();

        $totalTarefasConcluído = DB::table('tarefas')
            ->select('tarefas.id_tarefa')
            ->join('cartoes', 'cartoes.id_cartao', '=', 'tarefas.id_cartao')
            ->join('quadros', 'quadros.id_quadro', '=', 'cartoes.id_quadro')
            ->join('users', 'users.id_user', '=', 'quadros.id_user')
            ->where( [ 
                ['users.id_user','=',Auth::user()->id_user],
                ['users.id_empresa','=',Auth::user()->id_empresa],
                ['tarefas.status','=','CO']
        ])->get();

        $result['success'] = true;
        $result['tarefas_pendente'] = COUNT($totalTarefasPendente);
        $result['tarefas_fazendo'] = COUNT($totalTarefasFazendo);
        $result['tarefas_parada'] = COUNT($totalTarefasParado);
        $result['tarefas_concluido'] = COUNT($totalTarefasConcluído);
        echo json_encode($result);
    }

    public function consultaTarefas(Request $request)
    {
        $totalTarefasPendente = DB::table('tarefas')
            ->select('tarefas.id_tarefa')
            ->join('cartoes', 'cartoes.id_cartao', '=', 'tarefas.id_cartao')
            ->join('quadros', 'quadros.id_quadro', '=', 'cartoes.id_quadro')
            ->join('users', 'users.id_user', '=', 'quadros.id_user')
            ->where( [ 
                ['quadros.id_quadro','=',$request->id_quadro],
                ['cartoes.id_cartao','=',$request->id_cartao],
                ['tarefas.status','=','PE']
        ])->get();

        $totalTarefasFazendo = DB::table('tarefas')
            ->select('tarefas.id_tarefa')
            ->join('cartoes', 'cartoes.id_cartao', '=', 'tarefas.id_cartao')
            ->join('quadros', 'quadros.id_quadro', '=', 'cartoes.id_quadro')
            ->join('users', 'users.id_user', '=', 'quadros.id_user')
            ->where( [ 
                ['quadros.id_quadro','=',$request->id_quadro],
                ['cartoes.id_cartao','=',$request->id_cartao],
                ['tarefas.status','=','FA']
        ])->get();

        $totalTarefasParado = DB::table('tarefas')
            ->select('tarefas.id_tarefa')
            ->join('cartoes', 'cartoes.id_cartao', '=', 'tarefas.id_cartao')
            ->join('quadros', 'quadros.id_quadro', '=', 'cartoes.id_quadro')
            ->join('users', 'users.id_user', '=', 'quadros.id_user')
            ->where( [ 
                ['quadros.id_quadro','=',$request->id_quadro],
                ['cartoes.id_cartao','=',$request->id_cartao],
                ['tarefas.status','=','PA']
        ])->get();

        $totalTarefasConcluído = DB::table('tarefas')
            ->select('tarefas.id_tarefa')
            ->join('cartoes', 'cartoes.id_cartao', '=', 'tarefas.id_cartao')
            ->join('quadros', 'quadros.id_quadro', '=', 'cartoes.id_quadro')
            ->join('users', 'users.id_user', '=', 'quadros.id_user')
            ->where( [ 
                ['quadros.id_quadro','=',$request->id_quadro],
                ['cartoes.id_cartao','=',$request->id_cartao],
                ['tarefas.status','=','CO']
        ])->get();

        $result['success'] = true;
        $result['tarefas_pendente'] = COUNT($totalTarefasPendente);
        $result['tarefas_fazendo'] = COUNT($totalTarefasFazendo);
        $result['tarefas_parada'] = COUNT($totalTarefasParado);
        $result['tarefas_concluido'] = COUNT($totalTarefasConcluído);
        echo json_encode($result);
        
    }

    public function consultaTarefasAdm(Request $request)
    {
        if( $request->id_cartao>0 && $request->id_user>0)
        {
            $totalTarefasPendente = DB::table('tarefas')
                ->select('tarefas.id_tarefa')
                ->join('cartoes', 'cartoes.id_cartao', '=', 'tarefas.id_cartao')
                ->join('quadros', 'quadros.id_quadro', '=', 'cartoes.id_quadro')
                ->join('users', 'users.id_user', '=', 'quadros.id_user')
                ->where( [ 
                    ['quadros.id_quadro','=',$request->id_quadro],
                    ['cartoes.id_cartao','=',$request->id_cartao],
                    ['users.id_user','=',$request->id_user],
                    ['tarefas.status','=','PE']
            ])->get();

            $totalTarefasFazendo = DB::table('tarefas')
                ->select('tarefas.id_tarefa')
                ->join('cartoes', 'cartoes.id_cartao', '=', 'tarefas.id_cartao')
                ->join('quadros', 'quadros.id_quadro', '=', 'cartoes.id_quadro')
                ->join('users', 'users.id_user', '=', 'quadros.id_user')
                ->where( [ 
                    ['quadros.id_quadro','=',$request->id_quadro],
                    ['cartoes.id_cartao','=',$request->id_cartao],
                    ['users.id_user','=',$request->id_user],
                    ['tarefas.status','=','FA']
            ])->get();

            $totalTarefasParado = DB::table('tarefas')
                ->select('tarefas.id_tarefa')
                ->join('cartoes', 'cartoes.id_cartao', '=', 'tarefas.id_cartao')
                ->join('quadros', 'quadros.id_quadro', '=', 'cartoes.id_quadro')
                ->join('users', 'users.id_user', '=', 'quadros.id_user')
                ->where( [ 
                    ['quadros.id_quadro','=',$request->id_quadro],
                    ['cartoes.id_cartao','=',$request->id_cartao],
                    ['users.id_user','=',$request->id_user],
                    ['tarefas.status','=','PA']
            ])->get();

            $totalTarefasConcluído = DB::table('tarefas')
                ->select('tarefas.id_tarefa')
                ->join('cartoes', 'cartoes.id_cartao', '=', 'tarefas.id_cartao')
                ->join('quadros', 'quadros.id_quadro', '=', 'cartoes.id_quadro')
                ->join('users', 'users.id_user', '=', 'quadros.id_user')
                ->where( [ 
                    ['quadros.id_quadro','=',$request->id_quadro],
                    ['cartoes.id_cartao','=',$request->id_cartao],
                    ['users.id_user','=',$request->id_user],
                    ['tarefas.status','=','CO']
            ])->get();
        }
        else if( $request->id_cartao>0 && $request->id_user==0)
        {
            $totalTarefasPendente = DB::table('tarefas')
                ->select('tarefas.id_tarefa')
                ->join('cartoes', 'cartoes.id_cartao', '=', 'tarefas.id_cartao')
                ->join('quadros', 'quadros.id_quadro', '=', 'cartoes.id_quadro')
                ->join('users', 'users.id_user', '=', 'quadros.id_user')
                ->where( [ 
                    ['quadros.id_quadro','=',$request->id_quadro],
                    ['cartoes.id_cartao','=',$request->id_cartao],
                    ['tarefas.status','=','PE']
            ])->get();

            $totalTarefasFazendo = DB::table('tarefas')
                ->select('tarefas.id_tarefa')
                ->join('cartoes', 'cartoes.id_cartao', '=', 'tarefas.id_cartao')
                ->join('quadros', 'quadros.id_quadro', '=', 'cartoes.id_quadro')
                ->join('users', 'users.id_user', '=', 'quadros.id_user')
                ->where( [ 
                    ['quadros.id_quadro','=',$request->id_quadro],
                    ['cartoes.id_cartao','=',$request->id_cartao],
                    ['tarefas.status','=','FA']
            ])->get();

            $totalTarefasParado = DB::table('tarefas')
                ->select('tarefas.id_tarefa')
                ->join('cartoes', 'cartoes.id_cartao', '=', 'tarefas.id_cartao')
                ->join('quadros', 'quadros.id_quadro', '=', 'cartoes.id_quadro')
                ->join('users', 'users.id_user', '=', 'quadros.id_user')
                ->where( [ 
                    ['quadros.id_quadro','=',$request->id_quadro],
                    ['cartoes.id_cartao','=',$request->id_cartao],
                    ['tarefas.status','=','PA']
            ])->get();

            $totalTarefasConcluído = DB::table('tarefas')
                ->select('tarefas.id_tarefa')
                ->join('cartoes', 'cartoes.id_cartao', '=', 'tarefas.id_cartao')
                ->join('quadros', 'quadros.id_quadro', '=', 'cartoes.id_quadro')
                ->join('users', 'users.id_user', '=', 'quadros.id_user')
                ->where( [ 
                    ['quadros.id_quadro','=',$request->id_quadro],
                    ['cartoes.id_cartao','=',$request->id_cartao],
                    ['tarefas.status','=','CO']
            ])->get();
        }
        else
        {
            $totalTarefasPendente = DB::table('tarefas')
                ->select('tarefas.id_tarefa')
                ->join('cartoes', 'cartoes.id_cartao', '=', 'tarefas.id_cartao')
                ->join('quadros', 'quadros.id_quadro', '=', 'cartoes.id_quadro')
                ->join('users', 'users.id_user', '=', 'quadros.id_user')
                ->where( [ 
                    ['quadros.id_quadro','=',$request->id_quadro],
                    ['users.id_user','=',$request->id_user],
                    ['tarefas.status','=','PE']
            ])->get();

            $totalTarefasFazendo = DB::table('tarefas')
                ->select('tarefas.id_tarefa')
                ->join('cartoes', 'cartoes.id_cartao', '=', 'tarefas.id_cartao')
                ->join('quadros', 'quadros.id_quadro', '=', 'cartoes.id_quadro')
                ->join('users', 'users.id_user', '=', 'quadros.id_user')
                ->where( [ 
                    ['quadros.id_quadro','=',$request->id_quadro],
                    ['users.id_user','=',$request->id_user],
                    ['tarefas.status','=','FA']
            ])->get();

            $totalTarefasParado = DB::table('tarefas')
                ->select('tarefas.id_tarefa')
                ->join('cartoes', 'cartoes.id_cartao', '=', 'tarefas.id_cartao')
                ->join('quadros', 'quadros.id_quadro', '=', 'cartoes.id_quadro')
                ->join('users', 'users.id_user', '=', 'quadros.id_user')
                ->where( [ 
                    ['quadros.id_quadro','=',$request->id_quadro],
                    ['users.id_user','=',$request->id_user],
                    ['tarefas.status','=','PA']
            ])->get();

            $totalTarefasConcluído = DB::table('tarefas')
                ->select('tarefas.id_tarefa')
                ->join('cartoes', 'cartoes.id_cartao', '=', 'tarefas.id_cartao')
                ->join('quadros', 'quadros.id_quadro', '=', 'cartoes.id_quadro')
                ->join('users', 'users.id_user', '=', 'quadros.id_user')
                ->where( [ 
                    ['quadros.id_quadro','=',$request->id_quadro],
                    ['users.id_user','=',$request->id_user],
                    ['tarefas.status','=','CO']
            ])->get();
        }

        $result['success'] = true;
        $result['tarefas_pendente'] = COUNT($totalTarefasPendente);
        $result['tarefas_fazendo'] = COUNT($totalTarefasFazendo);
        $result['tarefas_parada'] = COUNT($totalTarefasParado);
        $result['tarefas_concluido'] = COUNT($totalTarefasConcluído);
        echo json_encode($result);
    }

    public function todasTarefasAdm(Request $request)
    {
        $totalTarefasPendente = DB::table('tarefas')
            ->select('tarefas.id_tarefa')
            ->join('cartoes', 'cartoes.id_cartao', '=', 'tarefas.id_cartao')
            ->join('quadros', 'quadros.id_quadro', '=', 'cartoes.id_quadro')
            ->join('users', 'users.id_user', '=', 'quadros.id_user')
            ->where( [ 
                ['users.id_empresa','=',Auth::user()->id_empresa],
                ['tarefas.status','=','PE']
        ])->get();

        $totalTarefasFazendo = DB::table('tarefas')
            ->select('tarefas.id_tarefa')
            ->join('cartoes', 'cartoes.id_cartao', '=', 'tarefas.id_cartao')
            ->join('quadros', 'quadros.id_quadro', '=', 'cartoes.id_quadro')
            ->join('users', 'users.id_user', '=', 'quadros.id_user')
            ->where( [ 
                ['users.id_empresa','=',Auth::user()->id_empresa],
                ['tarefas.status','=','FA']
        ])->get();

        $totalTarefasParado = DB::table('tarefas')
            ->select('tarefas.id_tarefa')
            ->join('cartoes', 'cartoes.id_cartao', '=', 'tarefas.id_cartao')
            ->join('quadros', 'quadros.id_quadro', '=', 'cartoes.id_quadro')
            ->join('users', 'users.id_user', '=', 'quadros.id_user')
            ->where( [ 
                ['users.id_empresa','=',Auth::user()->id_empresa],
                ['tarefas.status','=','PA']
        ])->get();

        $totalTarefasConcluído = DB::table('tarefas')
            ->select('tarefas.id_tarefa')
            ->join('cartoes', 'cartoes.id_cartao', '=', 'tarefas.id_cartao')
            ->join('quadros', 'quadros.id_quadro', '=', 'cartoes.id_quadro')
            ->join('users', 'users.id_user', '=', 'quadros.id_user')
            ->where( [ 
                ['users.id_empresa','=',Auth::user()->id_empresa],
                ['tarefas.status','=','CO']
        ])->get();

        $result['success'] = true;
        $result['tarefas_pendente'] = COUNT($totalTarefasPendente);
        $result['tarefas_fazendo'] = COUNT($totalTarefasFazendo);
        $result['tarefas_parada'] = COUNT($totalTarefasParado);
        $result['tarefas_concluido'] = COUNT($totalTarefasConcluído);
        echo json_encode($result);
    }

    public function tarefasUserAdm(Request $request)
    {
        DB::statement("SET SQL_MODE=''");//this is the trick use it just before your query
        $tarefas = DB::table('tarefas')
            ->select('tarefas_user.id_user','users.name',
                DB::raw('count(tarefas.id_tarefa) AS total')
            )->join('tarefas_user', 'tarefas_user.id_tarefa', '=', 'tarefas.id_tarefa')
            ->join('users', 'users.id_user', '=', 'tarefas_user.id_user')
            ->where( [ 
                ['users.id_empresa','=',Auth::user()->id_empresa],
                ['tarefas.status','=','PE']
            ])->groupBy('tarefas_user.id_user')
            ->orderBy('total', 'desc')
        ->limit(5)->get();

        foreach ($tarefas as $key => $tarefa) {
            $arrayName[] = $tarefa->name;
            $arrayTotal[] = $tarefa->total;
        }

        $result['tarefas'] = $tarefas;
        $result['names'] = $arrayName;
        $result['totais'] = $arrayTotal;

        $result['success'] = true;
        echo json_encode($result);
    }

    public function consultaCartoes(Request $request)
    {
        $cartoes = DB::table('cartoes')
            ->select('cartoes.id_cartao','cartoes.desc_cartao')
            ->where( [ 
                ['cartoes.id_quadro','=',$request->id_quadro]
        ])->orderBy('cartoes.desc_cartao', 'asc')->get();
        
        DB::statement("SET SQL_MODE=''");//this is the trick use it just before your query
        $users = DB::table('quadros')
            ->select('tarefas_user.id_user','users.name')
            ->join('cartoes', 'cartoes.id_quadro', '=', 'quadros.id_quadro')
            ->join('tarefas', 'tarefas.id_cartao', '=', 'cartoes.id_cartao')
            ->join('tarefas_user', 'tarefas_user.id_tarefa', '=', 'tarefas.id_tarefa')
            ->join('users', 'users.id_user', '=', 'tarefas_user.id_user')
            ->where( [ 
                ['quadros.id_quadro','=',$request->id_quadro]
            ])->groupBy('tarefas_user.id_user')
        ->orderBy('users.name', 'asc')->get();

        $result['cartoes'] = $cartoes;
        $result['users'] = $users;

        $result['success'] = true;
        echo json_encode($result);
    }

    
    
}
