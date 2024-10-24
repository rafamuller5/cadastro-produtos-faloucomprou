<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'Bem-vindo')</title> 
</head>
<body>
    <header>
        <h1>Bem-vindo ao Cadastro de Produtos</h1>
        <a href="{{ route('produtos.index') }}">Ver Produtos</a>
    </header>

    <main>
        
        @yield('content')
    </main>
</body>
</html>
