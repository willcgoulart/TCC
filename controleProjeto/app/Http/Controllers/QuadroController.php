<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Etiqueta;
use App\Models\Quadro;
use Illuminate\Http\Request;
use App\Http\Requests\QuadroRequest;
use Illuminate\Support\Facades\Auth;

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
    
}
