@extends('layout.layout')

@section('cabecalho')
  Usuários
@endsection

@section('conteudo')

  <section class="home-section">
    <div class="container">
      @include('routes', ['router' => 'form_cadastra_user'])
      @include('mensagem', ['mensagem' => $mensagem])
      <div class="alert alert-success" 
        id="mensagem_delete"
        style="display: none;">Usuário deletado com sucesso
      </div>

      <table class="table"> 
        <thead>
          <tr>
            <th scope="col" style="text-align: center">Nome</th>
            <th scope="col" style="text-align: center">Tipo</th>
            <th scope="col" style="text-align: center">Editar</th>
            <th scope="col" style="text-align: center">Excluir</th>
          </tr>
        </thead>
        <tbody>
        @foreach ($users as $user)
          <tr id="user_{{ $user->id_user }}">
            <td>{{ $user->name }}</td>
            <td style="text-align: center">
              {{ $user->tipoUser->desc_tipo_user }}
            </td>
            <td style="text-align: center">
              <a href="{{ route('form_editar_user', 
                ['id' => $user->id_user]) 
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
                onclick="modalUserExcluir( {{ $user->id_user }} );">
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
            id="excluir_user"
            data-excluir=""
          >Excluir</button>
        </div>
      </div>
    </div>
  </div>

  <script>

    function modalUserExcluir(idUser){
      $('#excluir_user').attr( 'data-excluir', idUser );
    }

    $('#excluir_user').click(function() {
      let idUser = $(this).attr('data-excluir');

      $.ajax({
        url: "{{route('form_deletar_user')}}",
        type: 'delete',
        data: { _token: '{{csrf_token()}}',id_user: idUser },
        dataType: 'json',

        success: function (response) {
          if(response['success'] == true){
            $('#user_'+idUser).remove();
            $('#mensagem_delete').css('display', '');
          }else{
            alert('Erro');
          }
        }
      });
    });
    
  </script>

@endsection