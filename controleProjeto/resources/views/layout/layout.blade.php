<!DOCTYPE html>
<html lang="pt-BR">

    @include('layout.head')

<body>

    @include('layout.sidebar')

    @include('layout.header')

    @yield('conteudo')

    @include('layout.footer')
    
</body>
</html>