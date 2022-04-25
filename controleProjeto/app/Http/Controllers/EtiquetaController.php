<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EtiquetaRequest;
use Illuminate\Support\Facades\Auth;

use App\Models\EtiquetaTipo;
use App\Models\Etiqueta;

use App\Services\EtiquetaService;

class EtiquetaController extends Controller
{

    protected $etiquetaService;

    function __construct(EtiquetaService $etiquetaService)
    {
        $this->etiquetaService = $etiquetaService;
    }

    public function index(Request $request)
    {
        if( Auth::user()->id_user_tipo==1 )
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
        }
        else
        {
            $etiquetas = Etiqueta::where( [ 
                    ['id_empresa','=',Auth::user()->id_empresa],
                    ['id_user','=',Auth::user()->id_user],
                    ['status','=','A'],
                    ['id_etiqueta_tipo','=','2']
                ] )->orderBy('desc_etiqueta')->get();
        }

        $mensagem = $request->session()->get('mensagem');

        return view('etiqueta.index', compact('etiquetas','mensagem'));
    }

    public  function create()
	{
        $tiposEtiquetas = EtiquetaTipo::query()->orderBy('desc_etiqueta_tipo')->get();       
        return view('etiqueta.create', compact('tiposEtiquetas'));
	}

    public function store(EtiquetaRequest $request)
	{
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
        
	}

    public function editar($idEtiqueta)
    {   
        $etiqueta = Etiqueta::find($idEtiqueta);
        $tiposEtiquetas = EtiquetaTipo::query()->orderBy('desc_etiqueta_tipo')->get();       
        
        return view('etiqueta.editar', compact('etiqueta','tiposEtiquetas'));
    }

    public function editarSalvar(EtiquetaRequest $request)
    {
        $etiqueta = Etiqueta::find($request->id_etiqueta);
        $etiqueta->desc_etiqueta = $request->desc_etiqueta;
        $etiqueta->id_etiqueta_tipo = $request->etiqueta_tipo;
        $etiqueta->cor_etiqueta = $request->cor_etiqueta;
        $etiqueta->save();

        $request->session()->flash('mensagem',"Etiqueta atualizado com sucesso");
        return redirect()->route('etiqueta');
    }

    public function destroy(Request $request)
    {
        $etiqueta = Etiqueta::find($request->id_etiqueta);
        $etiqueta->status = 'I';
        $etiqueta->save();

        $result['success'] = true;
        echo json_encode($result);
	}
    
}
