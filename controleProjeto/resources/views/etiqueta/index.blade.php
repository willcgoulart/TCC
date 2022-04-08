@extends('layout.layout')

@section('cabecalho')
  Etiqueta
@endsection

@section('conteudo')

  <section class="home-section">
    <div class="container">
      <a href="{{ route('form_criar_etiqueta') }}" class="btn btn-dark rounded-25 mb-2">Adicionar</a>
      <div id="etiqueta_tables"></div>
    </div>
  </section>

@endsection