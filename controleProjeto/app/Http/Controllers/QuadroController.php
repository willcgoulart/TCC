<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Etiqueta;
use Illuminate\Http\Request;
use App\Http\Requests\QuadroRequest;
use Illuminate\Support\Facades\Auth;

//use App\Services\EtiquetaService;

class QuadroController extends Controller
{
    /*
    protected $etiquetaService;

    function __construct(EtiquetaService $etiquetaService)
    {
        $this->etiquetaService = $etiquetaService;
    }
    */
    public function index()
    {
        return view('quadro.index');
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

    
    public function store(QuadroRequest $request)
	{
        dd( $request->request );
        /*
        $dado = $request->except( ['_token'] );
        $dado['id_empresa'] = Auth::user()->id_empresa;
        $dado['id_user'] = Auth::user()->id_user;

        if( isset($dado['etiqueta_tipo']) == false )
        {
            $dado['etiqueta_tipo'] = 2;
        }
        
        $etiqueta =  $this->etiquetaService->criarEtiqueta(
            $dado['desc_etiqueta'], 
            $dado['cor_etiqueta'], 
            $dado['etiqueta_tipo'],
            Auth::user()->id_empresa,
            Auth::user()->id_user
        );
        
        
		$request->session()->flash('mensagem',"Etiqueta cadastrda com sucesso");

        return view('etiqueta.index');
        */
        
	}
    
}
