@extends('layout.layout')

@section('cabecalho')
  Quadro
@endsection

@section('conteudo')
  <section class="home-section">
    <div class="container">

      @include('routes')
      
      <div class="row mt-2">

        @foreach ( $todosQuadros as $keyQuadro => $dados )
          @php 
              $arrayIdCartao = explode("|",$dados->id_cartao);
              $arrayDescCartao = explode("|",$dados->desc_cartao);
            @endphp
          <div class="col-xl-3 col-md-6 mb-4">
            <div class="form-label-group">
              <div class="card mt-2 rounded-10">
                <div class="card-header card-flex">
                  <div class="col mr-2 mt-2">
                    {{ $dados->desc_quadro }}
                  </div>
                  <div class="col-auto">
                    <button type="button"
                      class="btn btn-dark rounded-25" 
                      id="button_{{ $dados->id_quadro }}"
                      data-show="show"
                      onclick="liberaCartao( {{ $dados->id_quadro }} );">
                        <i class="bx bxs-message-square-add" id="img_{{ $dados->id_quadro }}"></i>
                    </button>
                  </div>
                </div>

                <div class="collapse multi-collapse" id="conteudo_{{ $dados->id_quadro }}">
                  @foreach ( $arrayIdCartao as $keyCartao => $cartao )
                    <div class="card-body card-flex">
                      <div class="col mr-2 mt-2">
                        {{ $arrayDescCartao[$keyCartao] }}
                      </div>
                      <div class="col-auto">
                        <a href="{{ route('tarefa_lista', 
                          ['id' => $arrayIdCartao[$keyCartao] ]) 
                        }}">
                          <button type="button" class="btn btn-light rounded-25">
                            <i class="bx bxs-edit"></i>
                          </button>
                        </a>
                      </div>
                    </div>
                    <hr>
                  @endforeach

                </div>
              </div>
            </div>
          </div>
        @endforeach

      </div>
    </div>
  </section>

  <script>
    function liberaCartao(dado){
      if( $('#button_'+dado).attr('data-show')=='show' ){
        $('#conteudo_'+dado).addClass('show');
        $('#button_'+dado).attr('data-show', '');

        $('#img_'+dado).removeClass('bxs-message-square-add');
        $('#img_'+dado).addClass('bxs-message-square-minus');
      }else{
        $('#conteudo_'+dado).removeClass('show');
        $('#button_'+dado).attr('data-show', 'show');
        $('#img_'+dado).removeClass('bxs-message-square-minus');
        $('#img_'+dado).addClass('bxs-message-square-add');
      }
    }

  </script>

@endsection