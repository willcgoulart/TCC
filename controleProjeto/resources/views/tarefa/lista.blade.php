@extends('layout.layout')

@section('cabecalho')
  Tarefa
@endsection

@section('conteudo')

  <section class="home-section">
    <div class="container">

      @include('routes')

      <div class="alert alert-success" id="mensagem_sucesso" style="display: none;">
        Alterado com sucesso!
      </div>

      <div class="row mt-2">
        @foreach ( $todasTarefas as $key => $dados )
          @php 
            $arrayIdTarefas = explode("|",$dados->id_tarefa);
            $arrayDescTarefas = explode("|",$dados->desc_tarefa);
            $arrayDataEntrega = explode("|",$dados->data_entrega);
            $arrayDataStatus = explode("|",$dados->status);
          @endphp
          <div class="col-xl-3 col-md-6 mb-4">
            <div class="form-label-group">
              {{ $dados->desc_quadro }}
              <div class="card mt-2 rounded-10">
                <div class="card-header">
                  {{ $dados->desc_cartao }}
                </div>
                @foreach ( $arrayDescTarefas as $keyTarefa => $tarefa )
                  <div class="card-body">
                    <p><b>{{ $tarefa }}</b></p>
                    @if( !empty($arrayDataEntrega[$keyTarefa]) )
                      <p><b>Data Entrega: </b>{{ $arrayDataEntrega[$keyTarefa] }}</p>
                    @endif
                    @switch( $arrayDataStatus[$keyTarefa] )
                      @case('PE')
                        <p class="custom-status-tarefa status-tarefa-DodgerBlue rounded-10" 
                          id="status_{{ $arrayIdTarefas[$keyTarefa] }}">
                          <b>Status: </b>Pendente
                        </p>
                      @break
                      @case('FA')
                        <p class="custom-status-tarefa status-tarefa-SandyBrown rounded-10" 
                          id="status_{{ $arrayIdTarefas[$keyTarefa] }}">
                          <b>Status: </b>Fazendo
                        </p>
                      @break
                      @case('PA')
                        <p class="custom-status-tarefa status-tarefa-Tomato rounded-10" 
                          id="status_{{ $arrayIdTarefas[$keyTarefa] }}">
                          <b>Status: </b>Parado
                        </p>
                      @break
                      @case('CO')
                        <p class="custom-status-tarefa status-tarefa-LimeGreen rounded-10" 
                          id="status_{{ $arrayIdTarefas[$keyTarefa] }}">
                          <b>Status: </b>Concluído
                        </p>
                      @break
                      @default
                        <p id="status_{{ $arrayIdTarefas[$keyTarefa] }}">
                          <b>Status: </b>Não Cadastrado
                        </p>
                    @endswitch
                    <button class="btn btn-dark rounded-25 detalha-tarefa"
                      data-tarefa="{{ $arrayIdTarefas[$keyTarefa] }}"
                      data-bs-toggle="modal" 
                      data-bs-target="#modalTarefa">
                      <i class="bx bx-search"></i> Detalhe
                    </button>
                    <hr>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <!-- Modal Tarefa -->
  <div class="modal fade mt-5" id="modalTarefa" data-bs-backdrop="static" 
    data-bs-keyboard="false" tabindex="-1" 
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Detalhamento</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="html_modal"></div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
          <button type="button" 
            class="btn btn-primary" 
            data-bs-dismiss="modal"
            id="id_tarefa"
            data-tarefa=""
          >Salvar</button>
        </div>
      </div>
    </div>
  </div>

  <script>

    $('.detalha-tarefa').click(function() {

      $('#id_tarefa').attr( 'data-tarefa', $(this).attr('data-tarefa') );
      $('#mensagem_sucesso').css('display', 'none');
      
      $.ajax({
        url: "{{route('tarefa_dados')}}",
        type: 'post',
        data: { _token: '{{csrf_token()}}',id_tarefa: $(this).attr('data-tarefa') },
        dataType: 'json',

        success: function (response) {
          if(response['success'] == true){

            let checkedPe = response['tarefa']['status']=='PE' ? 'checked' : '';
            let checkedFa = response['tarefa']['status']=='FA' ? 'checked' : '';
            let checkedPa = response['tarefa']['status']=='PA' ? 'checked' : '';
            let checkedCo = response['tarefa']['status']=='CO' ? 'checked' : '';

            let conteudo = '<p>'+response['tarefa']['desc_tarefa']+'</p>';

            if( response['tarefa']['data_entrega']!=null ){
              data = new Date(response['tarefa']['data_entrega']);
              dataFormatada = data.toLocaleDateString('pt-BR', {timeZone: 'UTC'});
              conteudo +='<p><b>Data Entrega: </b>'+dataFormatada+'</p>';
            }

            if( response['tarefa_etiqueta'].length>0 ){
              conteudo += '<p class="font-size-12"><b>Etiqueta(s): </b>';
              for (let i = 0; i < response['tarefa_etiqueta'].length; i++) {
                if( i>0 ){
                  conteudo += ', '+response['tarefa_etiqueta'][i]['desc_etiqueta'];
                }else{
                  conteudo += '<span class="custom-etiquetas-tarefa rounded-10 font-size-12"'+
                                'style="background-color: '+response['tarefa_etiqueta'][i]['cor_etiqueta']+'">'+
                                ''+response['tarefa_etiqueta'][i]['desc_etiqueta']+''+
                              '</span>';
                }
              }
              conteudo += '</p>';
            }

            if( response['tarefa_user'].length>0 ){
              conteudo += '<p class="font-size-12"><b>Usuário(s): </b>';
              for (let i = 0; i < response['tarefa_user'].length; i++) {
                if( i>0 ){
                  conteudo += ', '+response['tarefa_user'][i]['name'];
                }else{
                  conteudo += response['tarefa_user'][i]['name'];
                }
              }
              conteudo += '</p>';
            }

            if( response['tarefa_obs'].length>0 ){
              conteudo += '<p class="font-size-12"><b>Observação(s): </b></p>';
              for (let i = 0; i < response['tarefa_obs'].length; i++) {
                if( i>0 ){
                  conteudo += '<p class="font-size-12">'+response['tarefa_obs'][i]['desc_obs']+'</p>';
                }else{
                  conteudo += '<p class="font-size-12">'+response['tarefa_obs'][i]['desc_obs']+'</p>';
                }
              }
            }

            conteudo += '<div class="custom-control custom-radio my-1 mr-sm-2">'+
                          '<input type="radio" class="custom-control-input" '+
                            'name="status" '+
                            ' value="PE" '+
                            ''+checkedPe+'>'+
                          '<label class="custom-control-label custom-status-tarefa status-tarefa-DodgerBlue rounded-10 font-size-12">Pendente</label>'+  
                        '</div>'+
                        '<div class="custom-control custom-radio my-1 mr-sm-2">'+
                          '<input type="radio" class="custom-control-input" '+
                            'name="status" '+
                            ' value="FA" '+
                            ''+checkedFa+'>'+
                          '<label class="custom-control-label custom-status-tarefa status-tarefa-SandyBrown rounded-10 font-size-12">Fazendo</label>'+  
                        '</div>'+
                        '<div class="custom-control custom-radio my-1 mr-sm-2">'+
                          '<input type="radio" class="custom-control-input" '+
                            'name="status" '+
                            ' value="PA" '+
                            ''+checkedPa+'>'+
                          '<label class="custom-control-label custom-status-tarefa status-tarefa-Tomato rounded-10 font-size-12">Parado</label>'+  
                        '</div>'+
                        '<div class="custom-control custom-radio my-1 mr-sm-2">'+
                          '<input type="radio" class="custom-control-input" '+
                            'name="status" '+
                            ' value="CO" '+
                            ''+checkedCo+'>'+
                          '<label class="custom-control-label custom-status-tarefa status-tarefa-LimeGreen rounded-10 font-size-12">Concluído</label>'+  
                        '</div>';
          
            $('#html_modal').html(conteudo);
          }else{
            alert('Erro');
          }
        }
      });
    });

    $('#id_tarefa').click(function() {
      let tarefa = $(this).attr('data-tarefa');
      let status = $('[name=status]:checked').val();
      
      $.ajax({
        url: "{{route('tarefa_salvar')}}",
        type: 'post',
        data: { _token: '{{csrf_token()}}',
          id_tarefa: tarefa,
          status: status
        },
        dataType: 'json',

        success: function (response) {
          if(response['success'] == true){
            $('#status_'+tarefa).removeClass();
            switch(status) {
              case 'PE':
                $('#status_'+tarefa).html('<b>Status: </b> Pendente');
                $('#status_'+tarefa).addClass('custom-status-tarefa status-tarefa-DodgerBlue rounded-10');
                break;
              case 'FA':
                $('#status_'+tarefa).html('<b>Status: </b> Fazendo');
                $('#status_'+tarefa).addClass('custom-status-tarefa status-tarefa-SandyBrown rounded-10');
                break;
              case 'PA':
                $('#status_'+tarefa).html('<b>Status: </b> Parado');
                $('#status_'+tarefa).addClass('custom-status-tarefa status-tarefa-Tomato rounded-10');
                break;
              case 'CO':
                $('#status_'+tarefa).html('<b>Status: </b> Concluído');
                $('#status_'+tarefa).addClass('custom-status-tarefa status-tarefa-LimeGreen rounded-10');
                break;
              default:
                $('#status_'+tarefa).html('<b>Status: </b> Não Cadastrado');
            }
            $('#mensagem_sucesso').css('display', '');
          }else{
            alert('Erro');
          }
        }
      });
    });

  </script>

@endsection