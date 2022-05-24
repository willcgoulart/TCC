@extends('layout.layout')

@section('cabecalho')
  Quadro
@endsection

@section('conteudo')

  <section class="home-section">
    <div class="container">
      @include('routes')
      @include('erros', ['errors' => $errors->mensagemErro])

      <div class="form-label-group">
        <input type="text" 
          class="form-control rounded-25 mt-2" 
          id="desc_quadro" name="desc_quadro" 
          placeholder="Digite a Descrição do Quadro" 
          required="true" value="{{ old('desc_quadro') ?? '' }}"
          data-ivalid="ivalid-desc-quadro"
        />
        <label for="desc_quadro">Digite a Descrição do Quadro</label>
        <div class="invalid-feedback ivalid-desc-quadro"></div>

        <input type="hidden" 
          name="total_cartoes" id="total_cartoes" 
          value="{{ $cartoes }}" />
        <input type="hidden" 
          name="total_tarefas" id="total_tarefas" 
          value="{{ $tarefas }}" 
        />

        <button type="button" class="btn btn-dark rounded-25 mt-2 add-cartao">
          <i class="bx bxs-message-square-add"></i> Cartão
        </button>

        <div class="card mt-2 rounded-10 dados-cartoes" 
          data-cartoes="{{ $cartoes }}"
        >
          <div class="card-header card-group">
            <input type="text" 
              class="form-control cartoes rounded-25 mt-2" 
              name="desc_cartao_{{ $cartoes }}" id="desc_cartao_{{ $cartoes }}"
              placeholder="Digite a Descrição da Cartão" required="true" 
              data-cartao="{{ $cartoes }}"
              data-ivalid="ivalid-desc-cartao-{{ $cartoes }}"
            />
            <label for="desc_cartao_{{ $cartoes }}">Digite a Descrição da Cartão</label>
            <div class="invalid-feedback ivalid-desc-cartao-{{ $cartoes }}"></div>
          </div>
          <div class="card-body">
            <button type="button" 
              class="btn btn-dark rounded-25 mb-2 add-tarefa"
              data-cartao="{{ $cartoes }}"
              onclick="addTarefa( {{ $cartoes }} );"
            >
              <i class="bx bxs-message-square-add"></i> Tarefa
            </button>

            <table class="table"> 
              <thead>
                <tr>
                  <th scope="col">Tarefa</th>
                  <th scope="col">Status</th>
                  <th scope="col">Usuario</th>
                  <th scope="col">Etiqueta</th>
                  <th scope="col">Data Entrega</th>
                  <th scope="col">Obs</th>
                  <th scope="col">Excluir</th>
                </tr>
              </thead>
              <tbody>
                <tr class="dados-tarefas_{{ $cartoes }}" 
                  data-tarefa="{{ $tarefas }}">
                  <td>
                    <textarea class="form-control desc-tarefas" 
                      name="desc_tarefa_{{ $cartoes }}_{{ $tarefas }}" 
                      placeholder="Digite a Descrição da Tarefa" 
                      required="true"  rows="3"
                      data-ivalid="ivalid-desc-cartao-{{ $cartoes }}-{{ $tarefas }}"></textarea>
                      <div class="invalid-feedback ivalid-desc-cartao-{{ $cartoes }}-{{ $tarefas }}"></div>
                  </td>
                  <td>
                    <div class="custom-control custom-radio my-1 mr-sm-2">
                      <input type="radio" class="custom-control-input" 
                        name="status_{{ $cartoes }}_{{ $tarefas }}" 
                        value="PE" checked>
                      <label class="custom-control-label custom-status-tarefa status-tarefa-DodgerBlue rounded-10">Pendente</label>    
                    </div>
                    <div class="custom-control custom-radio my-1 mr-sm-2">
                      <input type="radio" class="custom-control-input" 
                        name="status_{{ $cartoes }}_{{ $tarefas }}" 
                        value="FA">
                      <label class="custom-control-label custom-status-tarefa status-tarefa-SandyBrown rounded-10">Fazendo</label>    
                    </div>
                    <div class="custom-control custom-radio my-1 mr-sm-2">
                      <input type="radio" class="custom-control-input" 
                        name="status_{{ $cartoes }}_{{ $tarefas }}" 
                        value="PA">
                      <label class="custom-control-label custom-status-tarefa status-tarefa-Tomato rounded-10">Parado</label>    
                    </div>
                    <div class="custom-control custom-radio my-1 mr-sm-2">
                      <input type="radio" class="custom-control-input" 
                        name="status_{{ $cartoes }}_{{ $tarefas }}" 
                        value="CO">
                      <label class="custom-control-label  custom-status-tarefa status-tarefa-LimeGreen rounded-10">Concluído</label>    
                    </div>
                  </td>
                  <td>
                    <div id="user_{{ $tarefas }}" style="font-size: 8.5px;"></div>
                    <input type="hidden" 
                      name="user_{{ $cartoes }}_{{ $tarefas }}"
                      value="" 
                    />
                    <button type="button" 
                      class="btn btn-light rounded-25 mt-4" 
                      data-bs-toggle="modal" 
                      data-bs-target="#modalUser"
                      onclick="modalUser({{ $cartoes }},{{ $tarefas }});"
                    >
                      <i class="bx bx-user"></i>
                    </button>
                  </td>
                  <td>
                    <div id="etiqueta_{{ $tarefas }}" style="font-size: 8.5px;"></div>
                    <input type="hidden" 
                      name="etiqueta_{{ $cartoes }}_{{ $tarefas }}"
                      value="" 
                    />
                    <button type="button" 
                      class="btn btn-light rounded-25 mt-4"
                      data-bs-toggle="modal" 
                      data-bs-target="#modalEtiqueta"
                      onclick="modalEtiqueta({{ $cartoes }},{{ $tarefas }});"
                    >
                      <i class="bx bx-purchase-tag-alt"></i>
                    </button>
                  </td>
                  <td>
                    <input type="date" 
                      class="form-control rounded-25 mt-4" 
                      name="data_{{ $cartoes }}_{{ $tarefas }}" 
                    />
                  </td>
                  <td>
                    <textarea class="form-control" 
                      name="obs_{{ $cartoes }}_{{ $tarefas }}" 
                      placeholder="Digite uma observação" 
                      rows="3"></textarea>
                  </td>
                  <td></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="row justify-content-center">
          <div class="col-xl-6 col-lg-6 col-md-9">
              <!-- Nested Row within Card Body -->
              <div class="row">
                  <div class="col-lg-12">
                      <button type="submit" class="btn btn-primary rounded-25 btn-block mt-2 mb-4" id="quadro_salvar">
                        <i class="fas fa-sign-in-alt fa-fw"></i>Salvar
                      </button>
                  </div>
              </div>
          </div>
      </div>
    </div>
  </section>
  <form action="{{ route('quadro') }}" method="GET" id="form_quadro"></form>

  <!-- Modal User -->
  <div class="modal fade mt-5" id="modalUser" data-bs-backdrop="static" 
    data-bs-keyboard="false" tabindex="-1" 
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Usuários</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          @foreach ($users as $user)
            <div class="form-group">
              <div class="custom-control custom-checkbox my-1 mr-sm-2">
                  <input type="checkbox" 
                    class="custom-control-input form-check-input lista_users"
                    data-name="{{ $user->name }}" 
                    value="{{ $user->id_user }}" />
                  <label class="custom-control-label  pt-1" for="preencherRespLegal">{{ $user->name }}</label>
              </div>
            </div>
          @endforeach
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
          <button type="button" 
            class="btn btn-primary" 
            data-bs-dismiss="modal"
            id="inserir_user"
            data-cartao="" data-tarefa=""
          >Salvar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Etiqueta -->
  <div class="modal fade mt-5" id="modalEtiqueta" data-bs-backdrop="static" 
    data-bs-keyboard="false" tabindex="-1" 
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Etiqueta</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          @foreach ($etiquetas as $etiqueta)
            <div class="form-group">
              <div class="custom-control custom-checkbox my-1 mr-sm-2">
                  <input type="checkbox" 
                    class="custom-control-input form-check-input lista_etiqueta"
                    data-descricao="{{ $etiqueta->desc_etiqueta }}"
                    data-cor-etiqueta="{{ $etiqueta->cor_etiqueta }}" 
                    value="{{ $etiqueta->id_etiqueta }}" />
                  <label class="custom-control-label custom-etiquetas-tarefa rounded-10" 
                    style="background-color: {{ $etiqueta->cor_etiqueta }};"
                    for="preencherRespLegal">
                      {{ $etiqueta->desc_etiqueta }}
                  </label>
              </div>
            </div>
          @endforeach
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
          <button type="button" 
            class="btn btn-primary" 
            data-bs-dismiss="modal"
            id="inserir_etiqueta"
            data-cartao="" data-tarefa=""
          >Salvar</button>
        </div>
      </div>
    </div>
  </div>
  
  <script>
    
    $('#quadro_salvar').click(function() {
      let erros = 0;
      const descQuadro = $('#desc_quadro');
      erros = validaRequerid(descQuadro,'Descrição do Quadro')+erros;

      $('.dados-cartoes').each(function( index ) {
        let descCartao = $('#desc_cartao_'+$(this).attr('data-cartoes'));
        erros = validaRequerid(descCartao,'Descrição da Cartão')+erros;
      });
      $('.desc-tarefas').each(function( index ) {
        erros = validaRequerid($(this),'Descrição da Tarefa')+erros;
      });

      if( erros==0 ){
        let descQuadro = $('#desc_quadro').val();
        var dadosCartoes = [];
        $('.dados-cartoes').each(function( index ) {
          let cartao = $(this).attr('data-cartoes');
          let dadosTarefas = [];
          $('.dados-tarefas_'+cartao).each(function( index ) {
            let tarefa = $(this).attr('data-tarefa');

            dadosTarefas[index] = { 
              desc_tarefa: $('[name=desc_tarefa_'+cartao+'_'+tarefa+']').val(),
              status: $('[name=status_'+cartao+'_'+tarefa+']:checked').val(),
              user: $('[name=user_'+cartao+'_'+tarefa+']').val(),
              etiqueta: $('[name=etiqueta_'+cartao+'_'+tarefa+']').val(),
              data_entrega: $('[name=data_'+cartao+'_'+tarefa+']').val(),
              obs: $('[name=obs_'+cartao+'_'+tarefa+']').val(),
            }
          });
          dadosCartoes[cartao] = {
            cartao: cartao,
            desc_cartao: $('#desc_cartao_'+cartao).val(),
            tarefas: dadosTarefas,
          }
        });
      
        $.ajax({
          url: "{{route('form_criar_quadro')}}",
          type: 'post',
          data: { _token: '{{csrf_token()}}',desc_quadro: descQuadro, dados_cartoes: dadosCartoes },
          dataType: 'json',

          success: function (response) {
            if(response['success'] == true){
              $('#form_quadro').submit();
            }else{
              alert('Erro');
            }
          }
        });

      }
    });

    $('.add-cartao').click(function() {
      let cartao = $('#total_cartoes').val();
      let tarefa = $('#total_tarefas').val();
      cartao++;
      tarefa++;

      let conteudo = conteudoCartao(cartao,tarefa);
      $('.dados-cartoes').last().after( conteudo );
      $('#total_cartoes').val(cartao);
      $('#total_tarefas').val(tarefa);
    });

    $('#inserir_user').click(function() {
      let cartao = $(this).attr('data-cartao');
      let tarefa = $(this).attr('data-tarefa');
      let idUser = '';
      let nameUser = '';

      $('.lista_users:checked').each(function( index ) {
        if( index==0 ){
          idUser = $(this).val();
          nameUser = $(this).attr('data-name');
        }else{
          idUser = idUser+'|'+$(this).val();
          nameUser = nameUser+', '+$(this).attr('data-name');
        }
      });

      $('#user_'+tarefa).html(nameUser);
      $('input[name="user_'+cartao+'_'+tarefa+'"]').val(idUser);

    });

    $('#inserir_etiqueta').click(function() {
      let cartao = $(this).attr('data-cartao');
      let tarefa = $(this).attr('data-tarefa');
      let idEtiqueta = '';
      let descEtiqueta = '';

      $('.lista_etiqueta:checked').each(function( index ) {
        if( index==0 ){
          idEtiqueta = $(this).val();
          descEtiqueta = '<span class="custom-etiquetas-tarefa rounded-10"'+
                          'style="background-color: '+$(this).attr('data-cor-etiqueta')+'">'+
                          $(this).attr('data-descricao')+
                        '</span>';
        }else{
          idEtiqueta = idEtiqueta+'|'+$(this).val();
          descEtiqueta = descEtiqueta+', '+
                      '<span class="custom-etiquetas-tarefa rounded-10"'+
                        'style="background-color: '+$(this).attr('data-cor-etiqueta')+'">'+
                        $(this).attr('data-descricao')+
                      '</span>';
        }
      });
      $('#etiqueta_'+tarefa).html(descEtiqueta);
      $('input[name="etiqueta_'+cartao+'_'+tarefa+'"]').val(idEtiqueta);

    });

    function conteudoTarefa(cartao,tarefa,buttonExcluir){
      let conteudo = '<tr class="dados-tarefas_'+cartao+'"'+ 
                'data-tarefa="'+tarefa+'">'+
                '<td>'+
                  '<textarea class="form-control desc-tarefas"'+
                    'name="desc_tarefa_'+cartao+'_'+tarefa+'"'+ 
                    'placeholder="Digite a Descrição da Tarefa" '+
                    'required="true"  rows="3"'+
                    'data-ivalid="ivalid-desc-cartao-'+cartao+'-'+tarefa+'"'+
                  '></textarea>'+
                  '<div class="invalid-feedback ivalid-desc-cartao-'+cartao+'-'+tarefa+'"></div>'+
                '</td>'+
                '<td>'+
                  '<div class="custom-control custom-radio my-1 mr-sm-2">'+
                    '<input type="radio" class="custom-control-input"'+ 
                      'name="status_'+cartao+'_'+tarefa+'" '+
                      'value="PE" checked>'+
                      '<label class="custom-control-label custom-status-tarefa status-tarefa-DodgerBlue rounded-10">Pendente</label>'+    
                  '</div>'+
                  '<div class="custom-control custom-radio my-1 mr-sm-2">'+
                    '<input type="radio" class="custom-control-input" '+
                      'name="status_'+cartao+'_'+tarefa+'"'+
                      'value="FA">'+
                      '<label class="custom-control-label custom-status-tarefa status-tarefa-SandyBrown rounded-10">Fazendo</label>'+    
                  '</div>'+
                  '<div class="custom-control custom-radio my-1 mr-sm-2">'+
                    '<input type="radio" class="custom-control-input"'+
                      'name="status_'+cartao+'_'+tarefa+'"'+
                      'value="PA">'+
                    '<label class="custom-control-label custom-status-tarefa status-tarefa-Tomato rounded-10">Parado</label>'+   
                  '</div>'+
                  '<div class="custom-control custom-radio my-1 mr-sm-2">'+
                    '<input type="radio" class="custom-control-input"'+ 
                      'name="status_'+cartao+'_'+tarefa+'"'+ 
                      'value="CO">'+
                    '<label class="custom-control-label  custom-status-tarefa status-tarefa-LimeGreen rounded-10">Concluído</label>'+    
                  '</div>'+
                '</td>'+
                '<td>'+
                  '<div id="user_'+tarefa+'" style="font-size: 8.5px;"></div>'+
                  '<input type="hidden"'+ 
                    'name="user_'+cartao+'_'+tarefa+'"'+
                    'value=""'+ 
                  '/>'+
                  '<button type="button"'+
                    'class="btn btn-light rounded-25 mt-4"'+ 
                    'data-bs-toggle="modal"'+ 
                    'data-bs-target="#modalUser"'+
                    'onclick="modalUser('+cartao+','+tarefa+');"'+
                  '>'+
                    '<i class="bx bx-user"></i>'+
                  '</button>'+
                '</td>'+
                '<td>'+
                  '<div id="etiqueta_'+tarefa+'" style="font-size: 8.5px;"></div>'+
                  '<input type="hidden"'+ 
                    'name="etiqueta_'+cartao+'_'+tarefa+'"'+
                    'value=""'+ 
                  '/>'+
                  '<button type="button"'+ 
                    'class="btn btn-light rounded-25 mt-4"'+
                    'data-bs-toggle="modal"'+ 
                    'data-bs-target="#modalEtiqueta"'+
                    'onclick="modalEtiqueta('+cartao+','+tarefa+');"'+
                  '>'+
                    '<i class="bx bx-purchase-tag-alt"></i>'+
                  '</button>'+
                '</td>'+
                '<td>'+
                  '<input type="date"'+ 
                    'class="form-control rounded-25 mt-4"'+ 
                    'name="data_'+cartao+'_'+tarefa+'"'+ 
                  '/>'+
                '</td>'+
                '<td>'+
                  '<textarea class="form-control"'+ 
                    'name="obs_'+cartao+'_'+tarefa+'"'+ 
                    'placeholder="Digite uma observação"'+ 
                    'rows="3"></textarea>'+
                '</td>'+
                '<td>';
                if( buttonExcluir=="S" ){
                  conteudo+='<button type="button"'+ 
                              'class="btn btn-danger rounded-25 mt-4"'+
                              'onclick="excluirTarefa( '+cartao+','+tarefa+' );">'+
                              '<i class="bx bxs-message-square-x"></i>'+
                            '</button>';
                }
      conteudo+='</td>'+
              '</tr>';
      return conteudo;
    }

    function conteudoCartao(cartao,tarefa){
      return '<button type="button"'+ 
                'class="btn btn-danger rounded-25 mt-4"'+
                'onclick="excluirCartao( this,'+cartao+' );">'+
                '<i class="bx bxs-message-square-x"></i> Excluir Cartão'+
              '</button>'+
              '<div class="card mt-2 rounded-10 dados-cartoes"'+
                'data-cartoes="'+cartao+'">'+
                '<div class="card-header card-group">'+
                  '<input type="text"'+ 
                    'class="form-control cartoes rounded-25 mt-2"'+ 
                    'name="desc_cartao_'+cartao+'" id="desc_cartao_'+cartao+'"'+
                    'placeholder="Digite a Descrição da Cartão" required="true"'+ 
                    'data-cartao="'+cartao+'"'+
                    'data-ivalid="ivalid-desc-cartao-'+cartao+'"/>'+
                  '<label for="desc_cartao_'+cartao+'">Digite a Descrição da Cartão</label>'+
                  '<div class="invalid-feedback ivalid-desc-cartao-'+cartao+'"></div>'+
                '</div>'+
                '<div class="card-body">'+
                  '<button type="button"'+ 
                    'class="btn btn-dark rounded-25 mb-2 add-tarefa"'+
                    'data-cartao="'+cartao+'"'+
                    'onclick="addTarefa( '+cartao+' );">'+
                    '<i class="bx bxs-message-square-add"></i> Tarefa'+
                  '</button>'+
                  '<table class="table">'+
                    '<thead>'+
                      '<tr>'+
                        '<th scope="col">Tarefa</th>'+
                        '<th scope="col">Status</th>'+
                        '<th scope="col">Usuario</th>'+
                        '<th scope="col">Etiqueta</th>'+
                        '<th scope="col">Data Entrega</th>'+
                        '<th scope="col">Obs</th>'+
                        '<th scope="col">Excluir</th>'+
                      '</tr>'+
                    '</thead>'+
                    '<tbody>'+
                      conteudoTarefa(cartao,tarefa,"N")+
                    '</tbody>'+
                  '</table>'+
                '</div>'+
              '</div>';
    }

    function addTarefa(cartao){
      let tarefa = $('#total_tarefas').val();
      tarefa++;
      let conteudo = conteudoTarefa(cartao,tarefa,"S");

      $('.dados-tarefas_'+cartao).last().after( conteudo );
      $('#total_tarefas').val(tarefa);
    }

    function excluirCartao(dado,cartao){
      $('.dados-cartoes').each(function( index ) {
        if( cartao==$(this).attr('data-cartoes') ){
          dado.remove();
          $(this).remove();
        }
      });
    }

    function excluirTarefa(cartao,tarefa){
      $('.dados-tarefas_'+cartao).each(function( index ) {
        if( tarefa==$(this).attr('data-tarefa') ){
          $(this).remove();
        }
      });
    }

    function modalUser(cartao,tarefa){
      $('#inserir_user').attr( 'data-cartao', cartao );
      $('#inserir_user').attr( 'data-tarefa', tarefa );
      $('.lista_users:checked').prop('checked', false);
    }

    function modalEtiqueta(cartao,tarefa){
      $('#inserir_etiqueta').attr( 'data-cartao', cartao );
      $('#inserir_etiqueta').attr( 'data-tarefa', tarefa );
      $('.lista_etiqueta:checked').prop('checked', false);
    }

    function validaRequerid(dado,descCampo){
      if( !dado.val() ){
        dado.addClass('is-invalid');
        $( '.'+dado.attr('data-ivalid') ).html('O campo '+descCampo+' é obrigatório!');
        return 1;
      }else{
        dado.removeClass('is-invalid');
        $( '.'+dado.attr('data-ivalid') ).html('');
        return 0;
      }
    }
  </script>

@endsection