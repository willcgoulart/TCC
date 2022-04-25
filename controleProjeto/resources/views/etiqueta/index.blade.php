@extends('layout.layout')

@section('cabecalho')
  Etiqueta
@endsection

@section('conteudo')

  <section class="home-section">
    <div class="container">
      @include('routes', ['router' => 'form_criar_etiqueta'])
      @include('mensagem', ['mensagem' => $mensagem])
      <div class="alert alert-success" 
        id="mensagem_delete"
        style="display: none;">Etiqueta deletado com sucesso
      </div>
      <table class="table"> 
        <thead>
          <tr>
            <th scope="col" style="text-align: center">Nome</th>
            <th scope="col" style="text-align: center">Cor</th>
            <th scope="col" style="text-align: center">Tipo</th>
            <th scope="col" style="text-align: center">Editar</th>
            <th scope="col" style="text-align: center">Excluir</th>
          </tr>
        </thead>
        <tbody>
        @foreach ($etiquetas as $etiqueta)
          <tr id="etiqueta_{{ $etiqueta->id_etiqueta }}">
            <td>{{ $etiqueta->desc_etiqueta }}</td>
            <td style="text-align: center">
              <span class="etiqueta-color" 
                style="background-color: {{ $etiqueta->cor_etiqueta }}">
              </span>
            </td>
            <td style="text-align: center">
              {{ $etiqueta->tipoEtiqueta->desc_etiqueta_tipo }}
            </td>
            <td style="text-align: center">
              <a href="{{ route('form_editar_etiqueta', 
                ['id' => $etiqueta->id_etiqueta]) 
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
                onclick="modalEtiquetaExcluir( {{ $etiqueta->id_etiqueta }} );">
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
          Tem certeza que deseja excluir a Etiqueta!
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
          <button type="button" 
            class="btn btn-danger" 
            data-bs-dismiss="modal"
            id="excluir_etiqueta"
            data-excluir=""
          >Excluir</button>
        </div>
      </div>
    </div>
  </div>

  <script>

    function modalEtiquetaExcluir(idEtiqueta){
      $('#excluir_etiqueta').attr( 'data-excluir', idEtiqueta );
    }

    $('#excluir_etiqueta').click(function() {
      let idEtiqueta = $(this).attr('data-excluir');

      $.ajax({
        url: "{{route('form_deletar_etiqueta')}}",
        type: 'delete',
        data: { _token: '{{csrf_token()}}',id_etiqueta: idEtiqueta },
        dataType: 'json',

        success: function (response) {
          if(response['success'] == true){
            $('#etiqueta_'+idEtiqueta).remove();
            $('#mensagem_delete').css('display', '');
          }else{
            alert('Erro');
          }
        }
      });
    });
    
  </script>

@endsection