<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
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
}
