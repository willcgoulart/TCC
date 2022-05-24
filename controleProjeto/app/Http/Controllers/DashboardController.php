<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Etiqueta;
use App\Models\Quadro;
use App\Models\TarefaUser;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $quadros = Quadro::where( [ 
            ['id_user','=',Auth::user()->id_user]
        ] )->get();

        $tarefasUser = DB::table('tarefas')
            ->select('tarefas.id_tarefa')
            ->join('tarefas_user', 'tarefas_user.id_tarefa', '=', 'tarefas.id_tarefa')
            ->where( [ 
                ['tarefas_user.id_user','=',Auth::user()->id_user],
                ['tarefas.status','=','PE']
        ]);
        $totalTarefas = DB::table('quadros')
            ->select('tarefas.id_tarefa')
            ->join('cartoes', 'cartoes.id_quadro', '=', 'quadros.id_quadro')
            ->join('tarefas', 'tarefas.id_cartao', '=', 'cartoes.id_cartao')
            ->where( [ 
                ['quadros.id_user','=',Auth::user()->id_user],
                ['tarefas.status','=','PE']
            ] )
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('tarefas_user')
                      ->whereColumn('tarefas_user.id_tarefa', 'tarefas.id_tarefa');
        })->union($tarefasUser)->get();

        $tarefasAtraso = DB::table('tarefas')
            ->select('tarefas.id_tarefa')
            ->join('cartoes', 'cartoes.id_cartao', '=', 'tarefas.id_cartao')
            ->join('quadros', 'quadros.id_quadro', '=', 'cartoes.id_quadro')
            ->join('tarefas_user', [ 
                ['tarefas_user.id_tarefa', '=', 'tarefas.id_tarefa'],
                ['tarefas_user.id_user', '=', 'quadros.id_user']
            ])->where( [ 
                ['tarefas_user.id_user','=',Auth::user()->id_user],
                ['tarefas.data_entrega','<=',date('Y-m-d')],
                ['tarefas.status','<>','CO']
        ]);
        $totalTarefasAtraso = DB::table('quadros')
            ->select('tarefas.id_tarefa')
            ->join('cartoes', 'cartoes.id_quadro', '=', 'quadros.id_quadro')
            ->join('tarefas', 'tarefas.id_cartao', '=', 'cartoes.id_cartao')
            ->where( [ 
                ['quadros.id_user','=',Auth::user()->id_user],
                ['tarefas.data_entrega','<=',date('Y-m-d')],
                ['tarefas.status','<>','CO']
            ] )
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('tarefas_user')
                      ->whereColumn('tarefas_user.id_tarefa', 'tarefas.id_tarefa');
        })->union($tarefasAtraso)->get();

        ### Dados ADM
        $totalTarefasAdm = DB::table('tarefas')
            ->select('tarefas.id_tarefa')
            ->join('cartoes', 'cartoes.id_cartao', '=', 'tarefas.id_cartao')
            ->join('quadros', 'quadros.id_quadro', '=', 'cartoes.id_quadro')
            ->join('users', 'users.id_user', '=', 'quadros.id_user')
            ->where( [ 
                ['users.id_empresa','=',Auth::user()->id_empresa],
                ['tarefas.status','=','PE']
        ])->get();

        $totalTarefasAtrasoAdm = DB::table('tarefas')
            ->select('tarefas.id_tarefa')
            ->join('cartoes', 'cartoes.id_cartao', '=', 'tarefas.id_cartao')
            ->join('quadros', 'quadros.id_quadro', '=', 'cartoes.id_quadro')
            ->join('users', 'users.id_user', '=', 'quadros.id_user')
            ->where( [ 
                ['users.id_empresa','=',Auth::user()->id_empresa],
                ['tarefas.data_entrega','<=',date('Y-m-d')],
                ['tarefas.status','<>','CO']
        ])->get();

        $totaltarefasUserAdm = DB::table('tarefas')
            ->select('tarefas_user.id_user',
                DB::raw('count(tarefas.id_tarefa) AS total')
            )->join('tarefas_user', 'tarefas_user.id_tarefa', '=', 'tarefas.id_tarefa')
            ->join('users', 'users.id_user', '=', 'tarefas_user.id_user')
            ->where( [ 
                ['users.id_empresa','=',Auth::user()->id_empresa],
                ['tarefas.status','=','PE']
        ])->groupBy('tarefas_user.id_user');
        $totaltarefasAdm = DB::table('quadros')
            ->select('quadros.id_user',
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
        ### Filtra somente users com mais de 10 tarefas pendetes
        $demandasUserAdm = 0;
        foreach ($totaltarefasAdmArray as $key => $value)
        {
           if( $value>9 )
           {
               $demandasUserAdm++;
           }
        }

        $totalQuadrosAdm = Quadro::join('users', 'users.id_user', '=', 'quadros.id_user')
            ->where( [ 
                ['users.id_empresa','=',Auth::user()->id_empresa]
        ] )->get();

        $mensagem = $request->session()->get('mensagem');
        return view('dashboard.index', compact(
            'quadros',
            'totalTarefas',
            'totalTarefasAtraso',
            'totalTarefasAdm',
            'totalTarefasAtrasoAdm',
            'demandasUserAdm',
            'totalQuadrosAdm',
            'mensagem'
        ));
    }

}
