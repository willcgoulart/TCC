@extends('layout.layout')

@section('cabecalho')
  Etiqueta
@endsection

@section('conteudo')

	<section class="home-section">
	<div class="container">
    @include('routes')
		<div class="row justify-content-center">
			<div class="col-xl-6 col-lg-6 col-md-9">
				<!-- Nested Row within Card Body -->
				<div class="row">
					<div class="col-lg-12">
						<div class="m-5">
							<div class="text-center">
								<h1 class="mb-4">Editar</h1>
							</div>
							<form method="POST" action="{{ route('form_salvar_editar_etiqueta') }}">
								@csrf
								@include('erros', ['errors' => $errors->mensagemErro])

								<div class="form-label-group">
									<input type="text" 
										class="form-control rounded-25 {{ $errors->has("desc_etiqueta") ? 'is-invalid' :'' }}" 
										id="desc_etiqueta" name="desc_etiqueta" 
										placeholder="Digite a Descrição" 
										required="true" 
										value="{{ old('desc_etiqueta') ?? $etiqueta->desc_etiqueta }}">
									<label for="desc_etiqueta">Digite a Descrição</label>
									<div class="invalid-feedback">
										@if($errors->has("desc_etiqueta"))
											@foreach($errors->get("desc_etiqueta") as $msg)
												{{$msg}}<br />
											@endforeach
										@endif
									</div>
								</div>
	
								@auth
									@if( Auth::user()->id_user_tipo==1 )
										<div class="form-group">
											<span style="font-size: 8px;">Tipo de Etiqueta</span>
										<div class="ml-4">
											@foreach ($tiposEtiquetas as $tipo)
												<div class="form-group">
													<div class="custom-control custom-radio my-1 mr-sm-2">
														<input type="radio" class="custom-control-input" 
															id="tipo_etiqueta_{{ $tipo->id_etiqueta_tipo }}"
															name="etiqueta_tipo" 
															value="{{ $tipo->id_etiqueta_tipo }}"
															{{ $tipo->id_etiqueta_tipo==$etiqueta->id_etiqueta_tipo ? 'checked' :'' }}>
														<label class="custom-control-label pt-1"
															for="tipo_etiqueta_{{ $tipo->id_etiqueta_tipo }}">
															{{ $tipo->desc_etiqueta_tipo }}</label>
													</div>
												</div>
											@endforeach
										</div>
									</div>
									@endif
								@endauth
								<div class="form-label-group ml-4 mt-2">
									<span class="etiqueta-color" 
										style="background-color: #519839"
										data-color="green"
										data-id="#519839">
										<i class='bx bx-check etiqueta-imagem-check'
										style="display: {{ $etiqueta->cor_etiqueta=='#519839' ? '' :'none' }};"></i>
									</span>
									<span class="etiqueta-color" 
										style="background-color: #d9b51c"
										data-color="yellow"
										data-id="#d9b51c">
										<i class='bx bx-check etiqueta-imagem-check'
										style="display: {{ $etiqueta->cor_etiqueta=='#d9b51c' ? '' :'none' }};"></i>
									</span>
									<span class="etiqueta-color" 
										style="background-color: #cd8313"
										data-color="orange"
										data-id="#cd8313">
										<i class='bx bx-check etiqueta-imagem-check'
										style="display: {{ $etiqueta->cor_etiqueta=='#cd8313' ? '' :'none' }};"></i>
									</span>
									<span class="etiqueta-color" 
										style="background-color: #b04632"
										data-color="red"
										data-id="#b04632">
										<i class='bx bx-check etiqueta-imagem-check'
										style="display: {{ $etiqueta->cor_etiqueta=='#b04632' ? '' :'none' }};"></i>
									</span>
									<span class="etiqueta-color" 
										style="background-color: #c377e0"
										data-color="purple"
										data-id="#c377e0">
										<i class='bx bx-check etiqueta-imagem-check'
										style="display: {{ $etiqueta->cor_etiqueta=='#c377e0' ? '' :'none' }};"></i>
									</span>
									<span class="etiqueta-color" 
										style="background-color: #0079bf"
										data-color="blue"
										data-id="#0079bf">
										<i class='bx bx-check etiqueta-imagem-check'
										style="display: {{ $etiqueta->cor_etiqueta=='#0079bf' ? '' :'none' }};"></i>
									</span>
									<span class="etiqueta-color" 
										style="background-color: #00c2e0"
										data-color="sky"
										data-id="#00c2e0">
										<i class='bx bx-check etiqueta-imagem-check'
										style="display: {{ $etiqueta->cor_etiqueta=='#00c2e0' ? '' :'none' }};"></i>
									</span>
									<span class="etiqueta-color" 
										style="background-color: #51e898"
										data-color="lime"
										data-id="#51e898">
										<i class='bx bx-check etiqueta-imagem-check'
										style="display: {{ $etiqueta->cor_etiqueta=='#51e898' ? '' :'none' }};"></i>
									</span>
									<span class="etiqueta-color" 
										style="background-color: #ff78cb"
										data-color="pink"
										data-id="#ff78cb">
										<i class='bx bx-check etiqueta-imagem-check'
										style="display: {{ $etiqueta->cor_etiqueta=='#ff78cb' ? '' :'none' }};"></i>
									</span>
									<span class="etiqueta-color" 
										style="background-color: #344563"
										data-color="black"
										data-id="#344563">
										<i class='bx bx-check etiqueta-imagem-check'
										style="display: {{ $etiqueta->cor_etiqueta=='#344563' ? '' :'none' }};"></i>
									</span>
									<span class="etiqueta-color" 
										style="background-color: #b3bac5"
										data-color="default"
										data-id="#b3bac5">
										<i class='bx bx-check etiqueta-imagem-check'
                    style="display: {{ $etiqueta->cor_etiqueta=='#b3bac5' ? '' :'none' }};"></i>
									</span>
									<input type="hidden" 
										name="cor_etiqueta" 
										id="cor_etiqueta"
										data-cor="{{ $etiqueta->cor_etiqueta }}"
										value="{{ $etiqueta->cor_etiqueta }}">
								</div>
	
								<button type="submit" 
									class="btn btn-primary rounded-25 btn-block mt-2" 
									id="btnEntrar">
									<i class="fas fa-sign-in-alt fa-fw" id="iconeEntrar"></i>Salvar
								</button>
                <input type="hidden" 
                  name="id_etiqueta" 
                  value="{{ $etiqueta->id_etiqueta }}">
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		$('.etiqueta-color').click(function() {
			let dataCor = $('#cor_etiqueta').attr('data-cor');

			$('.etiqueta-color').each(function( index ) {
				if( $(this).attr('data-id')==dataCor ){
					$(this).children().css({display: 'none'});
				}
      });
			$(this).children().css({display: ''});

      $('#cor_etiqueta').attr( 'data-cor', $(this).attr('data-id') );
      $('#cor_etiqueta').val( $(this).attr('data-id') );
		});
	</script>

@endsection