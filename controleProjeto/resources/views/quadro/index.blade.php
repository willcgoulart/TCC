@extends('layout.layout')

@section('cabecalho')
  Quadro
@endsection

@section('conteudo')

  <section class="home-section">
    <div class="container">

      @include('routes', ['router' => 'form_criar_quadro'])
      
    </div>
  </section>

@endsection