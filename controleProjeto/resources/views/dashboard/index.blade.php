@extends('layout.layout')

@section('cabecalho')
  Dashboard
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
                    Tarefas Pendentes: {{ count($totalTarefas) }}</div>
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

        <div class="col-xl-3 col-md-6 mb-4">
          <a href="{{ route('tarefa_atraso') }}" class="text-decoration-none">
          <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                    Tarefas Atraso: {{ count($totalTarefasAtraso) }}</div>
                  <div class="h5 mb-0 font-weight-bold text-gray-800"></div>
                </div>
                <div class="col-auto">
                  <i class='bx bx-task-x icone-card'></i>
                </div>
              </div>
            </div>
          </div>
          </a>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
          <a href="{{ route('lista_quadros') }}" class="text-decoration-none">
          <div class="card border-left-magentaBlue shadow h-100 py-2">
            <div class="card-body">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                    Quadros: {{ count($quadros) }}</div>
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

        @auth
          @if( Auth::user()->id_user_tipo==1 )
            <div class="col-lg-12">
              <div class="m-5">
                <div class="text-center">
                  <h1 class="mb-4">Dados Geral da Aplicação</h1>
                </div>
              </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
              <a href="{{ route('tarefa_pendente_adm') }}" class="text-decoration-none">
              <div class="card border-left-magentaBlue shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Tarefas Pendentes: {{ count($totalTarefasAdm) }}</div>
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

            <div class="col-xl-3 col-md-6 mb-4">
              <a href="{{ route('tarefa_atraso_adm') }}" class="text-decoration-none">
              <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Tarefas Atraso: {{ count($totalTarefasAtrasoAdm) }}</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"></div>
                    </div>
                    <div class="col-auto">
                      <i class='bx bx-task-x icone-card'></i>
                    </div>
                  </div>
                </div>
              </div>
              </a>
            </div>
    
            <div class="col-xl-3 col-md-6 mb-4">
              <a href="{{ route('tarefa_demanda') }}" class="text-decoration-none">
              <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Usuários Demanda: {{ $demandasUserAdm }}</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"></div>
                    </div>
                    <div class="col-auto">
                      <i class='bx bx-stopwatch icone-card'></i>
                    </div>
                  </div>
                </div>
              </div>
              </a>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
              <a href="{{ route('lista_quadros_adm') }}" class="text-decoration-none">
              <div class="card border-left-magentaBlue shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Quadros: {{ count($totalQuadrosAdm) }}</div>
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

          @endif
        @endauth
      </div>
    </div>
  </section>
  
@endsection