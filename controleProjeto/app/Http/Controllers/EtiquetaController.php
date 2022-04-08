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

    public function index()
    {
        return view('etiqueta.index');
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
    
}
