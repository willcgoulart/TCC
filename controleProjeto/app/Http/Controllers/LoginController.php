<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('login.index');
    }

    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->only(['email', 'password']))) {
            return redirect()
                ->back()
                ->withErrors('Usuário e/ou senha incorretos', 'mensagemErro');
        }
        return redirect()->route('dashboard');
    }   
    
    public function deslogar()
    {
        Auth::logout();
        return redirect()->route('login');
        //return view('entrar.index');
        //return back();
    }
}
