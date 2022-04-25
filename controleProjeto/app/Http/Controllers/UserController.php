<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserTipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Http\Requests\UserRequest;

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
        $userTipos = UserTipo::query()->orderBy('desc_tipo_user')->get(); 
        return view('user.create', compact('userTipos'));
    }

    public function store(UserRequest $request)
	{
        $data = $request->except( ['_token'] );
        $data['password'] = Hash::make($data['password']);
        $data['id_user_tipo'] = $request->user_tipo;
        $data['id_empresa'] = Auth::user()->id_empresa;

        User::create($data);
        
        $request->session()->flash('mensagem',"Usuário cadastrda com sucesso");
        return redirect()->route('user');    
	}

    public function editar($idUser)
    {   
        $user = User::find($idUser);
        $userTipos = UserTipo::query()->orderBy('desc_tipo_user')->get(); 
        
        return view('user.editar', compact('user','userTipos'));
    }

    public function editarSalvar(UserRequest $request)
    {
        $user = User::find($request->id_user);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->	id_user_tipo = $request->user_tipo;
        $user->	password = Hash::make($request->password);
        $user->save();

        $request->session()->flash('mensagem',"Usuário atualizado com sucesso");
        return redirect()->route('user');
    }

    public function destroy(Request $request)
    {
        $user = User::find($request->id_user);
        $user->status = 'I';
        $user->save();

        $result['success'] = true;
        echo json_encode($result);
	}
}
