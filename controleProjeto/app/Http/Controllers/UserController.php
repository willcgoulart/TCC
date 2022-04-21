<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {   
        $users =  User::where( [ 
            ['id_empresa','=',Auth::user()->id_empresa],
            ['status','=','A'] 
        ] )->orderBy('name', 'asc')->get(); 
        $mensagem = $request->session()->get('mensagem');
        return view('user.index', compact('users','mensagem'));
    }

    public function create()
    {
        return view('user.create');
    }

    public function store(Request $request)
	{
        ##Ainda nao ta pronto
        //$data = $request->except( ['_token','password_confirmation'] );
        $data = $request->except( ['_token'] );
        $data['password'] = Hash::make($data['password']);
        $data['id_user_tipo'] = 1;
        $data['id_empresa'] = 1;
        $user = User::create($data);
        
        //Auth::login($user);
        //return redirect()->route('dashboard_cliente');      
	}

    public function editar($idUser)
    {   
        dd('Aquii: '.$idUser);
        //$quadros = Quadro::query()->orderBy('desc_quadro')->get(); 
    
        
        return view('quadro.index', compact('quadros','mensagem'));
    }
}
