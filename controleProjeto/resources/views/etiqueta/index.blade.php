@extends('layout.layout')

@section('cabecalho')
  Etiqueta
@endsection

@section('conteudo')

  <section class="home-section">
    <div class="container">
      @include('routes', ['router' => 'form_criar_etiqueta'])
      <div id="etiqueta_tables"></div>
    </div>
  </section>

@endsection