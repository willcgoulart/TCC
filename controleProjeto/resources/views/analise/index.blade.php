@extends('layout.layout')

@section('cabecalho')
  Análise
@endsection

@section('conteudo')

  <section class="home-section">
    <div class="container">

      <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
          <select class="form-select quadro-user rounded-10" aria-label="Default select example">
            <option value="0" selected>Selecione o Quadro</option>
            @foreach ($totalQuadrosUser as $quadro)
              <option value="{{ $quadro->id_quadro }}">{{ $quadro->desc_quadro }}</option>
            @endforeach
          </select>

          <select class="form-select cartao-user mt-2 rounded-10" 
            aria-label="Default select example"
            id="cartoes_user">
            <option value="0" selected>Selecione o Cartão</option>
          </select>

          <button class="btn btn-dark mt-2 rounded-25 grafico-tarefa-user" disabled>
            <i class="bx bx-search"></i> Consultar
          </button>
        </div>

        @auth
          @if( Auth::user()->id_user_tipo==1 )
            <div class="col-xl-3 col-md-6 mb-4">
              <select class="form-select quadro-adm rounded-10" aria-label="Default select example">
                <option value="0" selected>Selecione o Quadro</option>
                @foreach ($todosQuadros as $quadro)
                  <option value="{{ $quadro->id_quadro }}">{{ $quadro->desc_quadro }}</option>
                @endforeach
              </select>

              <select class="form-select cartao-adm mt-2 rounded-10" 
                aria-label="Default select example"
                id="cartoes_adm">
                <option value="0" selected>Selecione o Cartão</option>
              </select>

              <select class="form-select user-adm mt-2 rounded-10" 
                aria-label="Default select example"
                id="user_adm">
                <option value="0" selected>Selecione o Usuário</option>
              </select>

              <button class="btn btn-dark mt-2 rounded-25 grafico-tarefa-adm" disabled>
                <i class="bx bx-search"></i> Consultar
              </button>
            </div>
          @endif
        @endauth

        <div class="row">
          <div class="col-xl-3 col-md-3 mb-3">
            <canvas id="graficoPizza"></canvas>
          </div>
          @auth
            @if( Auth::user()->id_user_tipo==1 )
              <div class="col-xl-3 col-md-3 mb-3">
                <canvas id="graficoPizzaAdm"></canvas>
              </div>
              <div class="col-xl-3 col-md-3 mb-3">
                <canvas id="graficoUserTaredasAdm"></canvas>
              </div>
            @endif
          @endauth
        </div>

      </div>
    </div>
  </section>

  <script>

    $(document).ready(function() {
      consultaTodasTarefas();

      consultaTodasTarefasAdm();
      consultaUserDemandasAdm();
    });
        
    $('.quadro-user').click(function() {
      if( $(this).val()>0 ){
        $.ajax({
          url: "{{route('analise_tarefas_cartoes')}}",
          type: 'post',
          data: { _token: '{{csrf_token()}}',id_quadro: $(this).val() },
          dataType: 'json',

          success: function (response) {
            if(response['success'] == true){
              let conteudo = '<option value="0" selected>Selecione o Cartão</option>';
              for (let i = 0; i < response['cartoes'].length; i++) {
                conteudo += '<option value="'+response['cartoes'][i]['id_cartao']+'">'+response['cartoes'][i]['desc_cartao']+'</option>';
              }
              $('#cartoes_user').html(conteudo);
              veriticaConsultaUser();
            }else{
              alert('Erro');
            }
          }
        });
      }
    }); 

    $('.cartao-user').click(function() {
      veriticaConsultaUser();
    }); 

    $('.grafico-tarefa-user').click(function() {
      $.ajax({
        url: "{{route('analise_tarefas')}}",
        type: 'post',
        data: { _token: '{{csrf_token()}}',
          id_quadro: $('.quadro-user').val(),
          id_cartao: $('.cartao-user').val()
        },
        dataType: 'json',

        success: function (response) {
          if(response['success'] == true){
            
            montaGraficoPizza(
              response['tarefas_pendente'],
              response['tarefas_fazendo'],
              response['tarefas_parada'],
              response['tarefas_concluido']
            );
          }else{
            alert('Erro');
          }
        }
      });
    }); 

    $('.quadro-adm').click(function() {
      if( $(this).val()>0 ){
        $.ajax({
          url: "{{route('analise_tarefas_cartoes')}}",
          type: 'post',
          data: { _token: '{{csrf_token()}}',id_quadro: $(this).val() },
          dataType: 'json',

          success: function (response) {
            if(response['success'] == true){
              let conteudoCartoes = '<option value="0" selected>Selecione o Cartão</option>';
              for (let i = 0; i < response['cartoes'].length; i++) {
                conteudoCartoes += '<option value="'+response['cartoes'][i]['id_cartao']+'">'+response['cartoes'][i]['desc_cartao']+'</option>';
              }
              $('#cartoes_adm').html(conteudoCartoes);

              let conteudoUsers = '<option value="0" selected>Selecione o Cartão</option>';
              for (let i = 0; i < response['users'].length; i++) {
                conteudoUsers += '<option value="'+response['users'][i]['id_user']+'">'+response['users'][i]['name']+'</option>';
              }
              $('#user_adm').html(conteudoUsers);

              veriticaConsultaAdm();
            }else{
              alert('Erro');
            }
          }
        });
      }
    }); 

    $('.cartao-adm').click(function() {
      veriticaConsultaAdm();
    }); 

    $('.user-adm').click(function() {
      veriticaConsultaAdm();
    }); 

    $('.grafico-tarefa-adm').click(function() {
      $.ajax({
        url: "{{route('analise_tarefas_adm')}}",
        type: 'post',
        data: { _token: '{{csrf_token()}}',
          id_quadro: $('.quadro-adm').val(),
          id_cartao: $('.cartao-adm').val(),
          id_user: $('.user-adm').val()
        },
        dataType: 'json',

        success: function (response) {
          if(response['success'] == true){
            
            montaGraficoPizzaAdm(
              response['tarefas_pendente'],
              response['tarefas_fazendo'],
              response['tarefas_parada'],
              response['tarefas_concluido']
            );
          }else{
            alert('Erro');
          }
        }
      });
    }); 
    
    function consultaTodasTarefas(){
      $.ajax({
        url: "{{route('analise_tarefas_todas')}}",
        type: 'post',
        data: { _token: '{{csrf_token()}}' },
        dataType: 'json',

        success: function (response) {
          if(response['success'] == true){
            montaGraficoPizza(
              response['tarefas_pendente'],
              response['tarefas_fazendo'],
              response['tarefas_parada'],
              response['tarefas_concluido']
            );
          }else{
            alert('Erro');
          }
        }
      }); 
    }

    function consultaTodasTarefasAdm(){
      $.ajax({
        url: "{{route('analise_tarefas_todas_adm')}}",
        type: 'post',
        data: { _token: '{{csrf_token()}}' },
        dataType: 'json',

        success: function (response) {
          if(response['success'] == true){
            montaGraficoPizzaAdm(
              response['tarefas_pendente'],
              response['tarefas_fazendo'],
              response['tarefas_parada'],
              response['tarefas_concluido']
            );
          }else{
            alert('Erro');
          }
        }
      }); 
    }

    function consultaUserDemandasAdm(){
      $.ajax({
        url: "{{route('analise_tarefas_user_adm')}}",
        type: 'post',
        data: { _token: '{{csrf_token()}}' },
        dataType: 'json',

        success: function (response) {
          if(response['success'] == true){

            const data = {
              labels: response['names'],
              datasets: [{
                label: 'Tarefas',
                data: response['totais'],
                backgroundColor: [
                  'rgba(255, 99, 132, 0.2)',
                  'rgba(255, 159, 64, 0.2)',
                  'rgba(75, 192, 192, 0.2)',
                  'rgba(54, 162, 235, 0.2)',
                  'rgba(153, 102, 255, 0.2)'
                ],
                borderColor: [
                  'rgb(255, 99, 132)',
                  'rgb(255, 159, 64)',
                  'rgb(75, 192, 192)',
                  'rgb(54, 162, 235)',
                  'rgb(153, 102, 255)'
                ],
                borderWidth: 1
              }]
            };

            const config = {
              type: 'bar',
              data: data,
              options: {
                scales: {
                  y: {
                    beginAtZero: true
                  }
                }
              },
            };

            const myChart = new Chart(
              document.getElementById('graficoUserTaredasAdm'),
              config
            );
          }else{
            alert('Erro');
          }
        }
      }); 
    }

    function veriticaConsultaUser(){
      if( $('.quadro-user').val()>0 && $('.cartao-user').val()>0 ){
        $('.grafico-tarefa-user').prop('disabled', false);
      }else{
        $('.grafico-tarefa-user').prop('disabled', true);
      }
    }

    function veriticaConsultaAdm(){    
      if( $('.quadro-adm').val()>0 && ( $('.cartao-adm').val()>0 || $('.user-adm').val()>0 ) ){
        $('.grafico-tarefa-adm').prop('disabled', false);
      }else{
        $('.grafico-tarefa-adm').prop('disabled', true);
      }
    }

    var myChart=null;
    var myChartAdm=null;
    function montaGraficoPizza(tarefasPendente,tarefasFazendo,tarefasParada,tarefasConcluido){
      const data = {
        labels: [
          'Pendente',
          'Fazendo',
          'Parado',
          'Concluído'
        ],
        datasets: [{
          label: 'Atividades',
          data: [
            tarefasPendente, 
            tarefasFazendo, 
            tarefasParada, 
            tarefasConcluido
          ],
          backgroundColor: [
            '#1E90FF',
            '#F4A460',
            '#FF6347',
            '#32CD32',
          ],
          hoverOffset: 4
        }]
      };
      const config = {
        type: 'doughnut',
        data: data,
      };
      if(myChart!=null){
        myChart.destroy();
      }
      myChart = new Chart(
        document.getElementById('graficoPizza'),
        config
      );
    }

    function montaGraficoPizzaAdm(tarefasPendente,tarefasFazendo,tarefasParada,tarefasConcluido){
      const data = {
        labels: [
          'Pendente',
          'Fazendo',
          'Parado',
          'Concluído'
        ],
        datasets: [{
          label: 'Atividades',
          data: [
            tarefasPendente, 
            tarefasFazendo, 
            tarefasParada, 
            tarefasConcluido
          ],
          backgroundColor: [
            '#1E90FF',
            '#F4A460',
            '#FF6347',
            '#32CD32',
          ],
          hoverOffset: 4
        }]
      };
      const config = {
        type: 'doughnut',
        data: data,
      };
      if(myChartAdm!=null){
        myChartAdm.destroy();
      }
      myChartAdm = new Chart(
        document.getElementById('graficoPizzaAdm'),
        config
      );
    }

  </script>
  
@endsection