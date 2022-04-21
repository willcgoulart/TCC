@extends('layout.layout')

@section('cabecalho')
    Usuários
@endsection

@section('conteudo')

  <section class="home-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-6 col-md-9">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="m-5">
                            <div class="text-center">
                                <h1 class="mb-4">Cadastro</h1>
                            </div> 
                            <form method="POST">
                                @csrf
                                @include('erros', ['errors' => $errors->mensagemErro])

                                <div class="form-label-group">
                                    <input type="text" class="form-control rounded-25 {{ $errors->has("name") ? 'is-invalid' :'' }}" 
                                    id="name" name="name" 
                                    placeholder="Digite seu Nome" 
                                    required="true" value="{{ old('name') ?? '' }}">
                                    <label for="name">Digite seu Nome</label>
                                    <div class="invalid-feedback">
                                        @if($errors->has("name"))
                                            @foreach($errors->get("name") as $msg)
                                            {{$msg}}<br />
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="form-label-group">
                                    <input type="email" class="form-control rounded-25 {{ $errors->has("email") ? 'is-invalid' :'' }}" 
                                    id="email" name="email" 
                                    placeholder="Digite seu E-mail" 
                                    required="true" value="{{ old('email') ?? '' }}">
                                    <label for="email">Digite seu E-mail</label>
                                    <div class="invalid-feedback">
                                        @if($errors->has("email"))
                                            @foreach($errors->get("email") as $msg)
                                            {{$msg}}<br />
                                            @endforeach
                                        @endif
                                    </div>
                                </div>

                                <div class="form-label-group">
                                    <input type="password" class="form-control rounded-25 {{ $errors->has("password") ? 'is-invalid' :'' }}" 
                                    id="password" name="password" 
                                    placeholder="Digite sua Senha" 
                                    required="true">
                                    <label for="password">Digite sua Senha</label>
                                    <div class="invalid-feedback">
                                        @if($errors->has("password"))
                                            @foreach($errors->get("password") as $msg)
                                            {{$msg}}<br />
                                            @endforeach
                                        @endif
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary rounded-25 btn-block" id="btnEntrar">
                                    <i class="fas fa-sign-in-alt fa-fw" id="iconeEntrar"></i>Salvar
                                </button>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection