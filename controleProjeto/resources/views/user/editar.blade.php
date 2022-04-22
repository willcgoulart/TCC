@extends('layout.layout')

@section('cabecalho')
    Usuários
@endsection

@section('conteudo')

  <section class="home-section">
    <div class="container">
        @include('routes')
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-6 col-md-9">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="m-5">
                            <div class="text-center">
                                <h1 class="mb-4">Editar</h1>
                            </div> 
                            <form method="POST" action="{{ route('form_salvar_editar_user') }}">
                                @csrf
                                @include('erros', ['errors' => $errors->mensagemErro])
                   
                                <div class="form-label-group">
                                    <input type="text" class="form-control rounded-25 
                                        {{ $errors->has("name") ? 'is-invalid' :'' }}" 
                                        id="name" name="name" 
                                        placeholder="Digite seu Nome" 
                                        value="{{ old('name') ?? $user->name }}">
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
                                    <input type="email" class="form-control rounded-25 
                                        {{ $errors->has("email") ? 'is-invalid' :'' }}" 
                                        id="email" name="email" 
                                        placeholder="Digite seu E-mail" 
                                        value="{{ old('email') ?? $user->email }}">
                                    <label for="email">Digite seu E-mail</label>
                                    <div class="invalid-feedback">
                                        @if($errors->has("email"))
                                            @foreach($errors->get("email") as $msg)
                                            {{$msg}}<br />
                                            @endforeach
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <span style="font-size: 8px;">Tipo de Usuário</span>
                                    <div class="ml-4">
                                        @foreach ($userTipos as $tipo)
                                            <div class="form-group">
                                                <div class="custom-control custom-radio my-1 mr-sm-2">
                                                    <input type="radio" class="custom-control-input" 
                                                    id="user_tipo_{{ $tipo->id_user_tipo }}"
                                                    name="user_tipo" 
                                                    value="{{ $tipo->id_user_tipo }}"
                                                    {{ $tipo->id_user_tipo==$user->id_user_tipo ? 'checked' :'' }}>
                                                    <label class="custom-control-label pt-1"
                                                    for="user_tipo_{{ $tipo->id_user_tipo }}">
                                                    {{ $tipo->desc_tipo_user }}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="form-label-group">
                                    <input type="password" class="form-control rounded-25 mt-3
                                        {{ $errors->has("password") ? 'is-invalid' :'' }}" 
                                        id="password" name="password" 
                                        placeholder="Digite sua Senha">
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
                                <input type="hidden" 
                                    name="id_user" 
                                    value="{{ $user->id_user }}">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection