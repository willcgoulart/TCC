@if(!empty($router))
    <a href="{{ route($router) }}" class="btn btn-dark rounded-25 mb-2">Adicionar</a>
@endif

<a href="{{ url()->previous() }}" class="btn btn-primary rounded-25 mb-2 float-right"> 
    <i class='bx bxs-left-arrow-circle'></i> Voltar
</a>