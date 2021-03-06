@extends('layout.layout')

@section('cabecalho')
  Quadro
@endsection

@section('conteudo')

  <section class="home-section">
    <div class="container">

      @include('routes', ['router' => 'form_criar_quadro'])
      @include('mensagem', ['mensagem' => $mensagem])
      <div class="alert alert-success" 
        id="mensagem_delete"
        style="display: none;">Quadro deletado com sucesso
      </div>

      <table class="table"> 
        <thead>
          <tr>
            <th scope="col" style="text-align: center">Quadro</th>
            <th scope="col" style="text-align: center">Total de Cartões</th>
            <th scope="col" style="text-align: center">Total de Tarefas</th>
            <th scope="col" style="text-align: center">Tarefas Concluidas %</th>
            <th scope="col" style="text-align: center">Editar</th>
            <th scope="col" style="text-align: center">Excluir</th>
          </tr>
        </thead>
        <tbody>
        @foreach ($quadros as $quadro)
          @php $totalTarefas=0; @endphp
          <tr id="quadro_{{ $quadro->id_quadro }}">
            <td>{{ $quadro['desc_quadro'] }}</td>
            <td style="text-align: center">{{ count( $quadro->cartao ) }}</td>
            <td style="text-align: center">
              @foreach ( $quadro->cartao as $cartao )
                @php $totalTarefas=$totalTarefas+count( $cartao->tarefa ) @endphp
              @endforeach
              {{ $totalTarefas }}
            </td>
            <td style="text-align: center">
              @php $totalTarefasConcluidas=0; @endphp
              @foreach ( $quadro->cartao as $cartao )
                @foreach ( $cartao->tarefa as $tarefa )
                  @if ($tarefa->status=="CO")
                    @php $totalTarefasConcluidas++; @endphp
                  @endif
                @endforeach
              @endforeach
              {{ round( ( ( $totalTarefasConcluidas*100 )/$totalTarefas ),2 ) }}%
            </td>
            <td style="text-align: center">
              <a href="{{ route('form_editar_quadro', 
                ['id' => $quadro->id_quadro]) 
              }}">
                <button type="button" 
                  class="btn btn-light rounded-25">
                  <i class='bx bxs-edit'></i>
                </button>
              </a>
            </td>
            <td style="text-align: center">
              <button type="button" 
                class="btn btn-danger rounded-25" 
                data-bs-toggle="modal" 
                data-bs-target="#modalExcluir"
                onclick="modalQuadroExcluir( {{ $quadro->id_quadro }} );">
                <i class="bx bxs-message-square-x"></i>
              </button>
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>
      
    </div>
  </section>

  <!-- Modal Excluir -->
  <div class="modal fade mt-5" id="modalExcluir" data-bs-backdrop="static" 
    data-bs-keyboard="false" tabindex="-1" 
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Excluir</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Tem certeza que deseja excluir o quadro!
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
          <button type="button" 
            class="btn btn-danger" 
            data-bs-dismiss="modal"
            id="excluir_quadro"
            data-excluir=""
          >Excluir</button>
        </div>
      </div>
    </div>
  </div>

  <script>

    function modalQuadroExcluir(quadro){
      $('#excluir_quadro').attr( 'data-excluir', quadro );
    }

    $('#excluir_quadro').click(function() {
      let quadro = $(this).attr('data-excluir');

      $.ajax({
        url: "{{route('form_deletar_quadro')}}",
        type: 'delete',
        data: { _token: '{{csrf_token()}}',id_quadro: quadro },
        dataType: 'json',

        success: function (response) {
          if(response['success'] == true){
            $('#quadro_'+quadro).remove();
            $('#mensagem_delete').css('display', '');
          }else{
            alert('Erro');
          }
        }
      });
    });
    
  </script>

@endsection