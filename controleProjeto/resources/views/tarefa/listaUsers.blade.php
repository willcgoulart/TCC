@extends('layout.layout')

@section('cabecalho')
  Tarefa
@endsection

@section('conteudo')

  <section class="home-section">
    <div class="container">

      @include('routes')

      <div class="row">
        @foreach ( $dados as $key => $value )
          <div class="col-xl-3 col-md-6 mb-4">
            <a href="{{ route('tarefa_lista_user', 
                ['id' => $value['id'] ]) 
              }}" 
              class="text-decoration-none">
            <div class="card border-left-magentaBlue shadow h-100 py-2">
              <div class="card-body">
                <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                      {{ $value['name'] }}: {{ $value['total'] }}</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"></div>
                  </div>
                  <div class="col-auto">
                    <i class='bx bx-card bx-user'></i>
                  </div>
                </div>
              </div>
            </div>
            </a>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  

@endsection