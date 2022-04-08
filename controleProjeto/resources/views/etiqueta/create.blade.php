@extends('layout.layout')

@section('cabecalho')
  Etiqueta
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
                                    <input type="text" class="form-control rounded-25 {{ $errors->has("desc_etiqueta") ? 'is-invalid' :'' }}" 
                                    id="desc_etiqueta" name="desc_etiqueta" 
                                    placeholder="Digite a Descrição" 
                                    required="true" value="{{ old('desc_etiqueta') ?? '' }}">
                                    <label for="desc_etiqueta">Digite a Descrição</label>
                                    <div class="invalid-feedback">
                                        @if($errors->has("desc_etiqueta"))
                                            @foreach($errors->get("desc_etiqueta") as $msg)
                                            {{$msg}}<br />
                                            @endforeach
                                        @endif
                                    </div>
                                </div>

                                @auth
                                    @if( Auth::user()->id_user_tipo==1 )
                                        <div class="form-group">
                                            <span style="font-size: 8px;">Tipo de Etiqueta</span>
                                            <div class="ml-4">
                                                @foreach ($tiposEtiquetas as $tipo)
                                                    <div class="form-group">
                                                        <div class="custom-control custom-radio my-1 mr-sm-2">
                                                            <input type="radio" class="custom-control-input" 
                                                            id="tipo_etiqueta_{{ $tipo->id_etiqueta_tipo }}"
                                                            name="etiqueta_tipo" 
                                                            value="{{ $tipo->id_etiqueta_tipo }}"
                                                            {{ $tipo->id_etiqueta_tipo==2 ? 'checked' :'' }}>
                                                            <label class="custom-control-label pt-1"
                                                            for="tipo_etiqueta_{{ $tipo->id_etiqueta_tipo }}">
                                                            {{ $tipo->desc_etiqueta_tipo }}</label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endauth
                                <div class="form-label-group ml-4" id="etiqueta_create"></div>

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