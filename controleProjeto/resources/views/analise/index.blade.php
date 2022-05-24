@extends('layout.layout')

@section('cabecalho')
  Análise
@endsection

@section('conteudo')

  <section class="home-section">
    <div class="container">

      <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
          <a href="{{ route('tarefa_pendente') }}" class="text-decoration-none">
          <div class="card border-left-magentaBlue shadow h-100 py-2">
            <div class="card-body">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                    Tarefas Pendentes: dddd</div>
                  <div class="h5 mb-0 font-weight-bold text-gray-800"></div>
                </div>
                <div class="col-auto">
                  <i class='bx bx-card icone-card'></i>
                </div>
              </div>
            </div>
          </div>
          </a>
        </div>

       

      
      </div>
    </div>
  </section>
  
@endsection