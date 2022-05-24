<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Etiqueta;
use App\Models\Quadro;
use Illuminate\Http\Request;
use App\Http\Requests\QuadroRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Services\QuadroService;

class QuadroController extends Controller
{
    protected $quadroService;

    function __construct(QuadroService $quadroService)
    {
        $this->quadroService = $quadroService;
    }
    
    public function index(Request $request)
    {   
        //$user = User::find($request->id_user);
        $quadros = Quadro::query()->orderBy('desc_quadro')->get(); 
    
        $mensagem = $request->session()->get('mensagem');
        return view('quadro.index', compact('quadros','mensagem'));
    }

    public function create()
	{
        $etiquetas = Etiqueta::where( [ 
            ['id_empresa','=',Auth::user()->id_empresa],
            ['status','=','A'],
            ['id_etiqueta_tipo','=','1'] 
        ] )->union(Etiqueta::where( [ 
                ['id_empresa','=',Auth::user()->id_empresa],
                ['id_user','=',Auth::user()->id_user],
                ['status','=','A'],
                ['id_etiqueta_tipo','=','2']
            ] )
        )->orderBy('desc_etiqueta')->get();   
        
        $users =  User::where( [ 
            ['id_empresa','=',Auth::user()->id_empresa],
            ['status','=','A'] 
        ] )->orderBy('name', 'asc')->get();

        $cartoes = 1;
        $tarefas = 1;

        return view( 'quadro.create', compact( 'users', 'etiquetas', 'cartoes', 'tarefas' ) );
	}

    public function store(Request $request)
	{
        $dados = $request->except( ['_token'] );
       
        $quadro =  $this->quadroService->criarQuadro($dados);
		$request->session()->flash('mensagem',"Quadro cadastrda com sucesso");

        $result['success'] = true;
        echo json_encode($result);
	}

    public function editar($quadroId)
    {
        $idQuadro = $quadroId;
        $dadosQuadros = Quadro::find($idQuadro);
        
        $etiquetas = Etiqueta::where( [ 
            ['id_empresa','=',Auth::user()->id_empresa],
            ['status','=','A'],
            ['id_etiqueta_tipo','=','1'] 
        ] )->union(Etiqueta::where( [ 
                ['id_empresa','=',Auth::user()->id_empresa],
                ['id_user','=',Auth::user()->id_user],
                ['status','=','A'],
                ['id_etiqueta_tipo','=','2']
            ] )
        )->orderBy('desc_etiqueta')->get(); 

        $users =  User::where( [ 
            ['id_empresa','=',Auth::user()->id_empresa],
            ['status','=','A'] 
        ] )->orderBy('name', 'asc')->get();

        $cartoes = 1;
        $tarefas = 1;

        return view('quadro.editar', compact( 
            'users', 
            'etiquetas', 
            'cartoes', 
            'tarefas', 
            'dadosQuadros',
            'idQuadro'
        ));
    }

    public function editarSalvar(Request $request)
    {
        $this->quadroService->deletarQuadro($request->id_quadro);
        $dados = $request->except( ['_token'] );
       
        $this->quadroService->criarQuadro($dados);
		$request->session()->flash('mensagem',"Quadro atualizado com sucesso");

        $result['success'] = true;
        echo json_encode($result);
    }

    public function destroy(Request $request)
    {
        $quadro =  $this->quadroService->deletarQuadro($request->id_quadro);
        $result['success'] = true;
        echo json_encode($result);
	}

    public function listaQuadros(Request $request)
    {
        DB::statement("SET SQL_MODE=''");//this is the trick use it just before your query
        $quadroUser = DB::table('quadros')
            ->select('quadros.id_quadro','quadros.desc_quadro',
                DB::raw('GROUP_CONCAT(cartoes.id_cartao ORDER BY cartoes.id_cartao ASC SEPARATOR "|") AS id_cartao'),
                DB::raw('GROUP_CONCAT(cartoes.desc_cartao ORDER BY cartoes.id_cartao ASC SEPARATOR "|") AS desc_cartao'))
            ->join('cartoes', 'cartoes.id_quadro', '=', 'quadros.id_quadro')
            ->where( [ 
                ['quadros.id_user','=',Auth::user()->id_user]
        ])->groupBy('quadros.id_quadro');
        
        $todosQuadros = DB::table('tarefas_user')
            ->select('quadros.id_quadro','quadros.desc_quadro',
                DB::raw('GROUP_CONCAT(DISTINCT cartoes.id_cartao ORDER BY cartoes.id_cartao ASC SEPARATOR "|") AS id_cartao'),
                DB::raw('GROUP_CONCAT(DISTINCT cartoes.desc_cartao ORDER BY cartoes.id_cartao ASC SEPARATOR "|") AS desc_cartao'))
            ->join('tarefas', 'tarefas.id_tarefa', '=', 'tarefas_user.id_tarefa')
            ->join('cartoes', 'cartoes.id_cartao', '=', 'tarefas.id_cartao')
            ->join('quadros', 'quadros.id_quadro', '=', 'cartoes.id_quadro')
            ->where( [ 
                ['quadros.id_user','=',Auth::user()->id_user]
            ])->groupBy('quadros.id_quadro')
        ->union($quadroUser)->get();

        $mensagem = $request->session()->get('mensagem');
        return view('quadro.lista', compact('todosQuadros','mensagem'));
    }

    public function listaQuadrosAdm(Request $request)
    {
        DB::statement("SET SQL_MODE=''");//this is the trick use it just before your query
        $todosQuadros = DB::table('quadros')
            ->select('quadros.id_quadro','quadros.desc_quadro',
                DB::raw('GROUP_CONCAT(cartoes.id_cartao ORDER BY cartoes.id_cartao ASC SEPARATOR "|") AS id_cartao'),
                DB::raw('GROUP_CONCAT(cartoes.desc_cartao ORDER BY cartoes.id_cartao ASC SEPARATOR "|") AS desc_cartao'))
            ->join('cartoes', 'cartoes.id_quadro', '=', 'quadros.id_quadro')
        ->groupBy('quadros.id_quadro')->get();
        
        $mensagem = $request->session()->get('mensagem');
        return view('quadro.lista', compact('todosQuadros','mensagem'));
    }
    
}
