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

class AnaliseController extends Controller
{    
    public function index(Request $request)
    {   
        //$user = User::find($request->id_user);
        $quadros = Quadro::query()->orderBy('desc_quadro')->get(); 
    
        $mensagem = $request->session()->get('mensagem');
        return view('analise.index', compact('quadros','mensagem'));
    }

    
}
