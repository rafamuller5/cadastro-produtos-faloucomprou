<!DOCTYPE html>
<html>
<head>
    <title>Página de Boas-Vindas</title>
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}"> 
</head>
<body>
    <div class="container">
        <img src="{{ asset('images/logo.png') }}" alt="Logo da Empresa" class="logo"> 
        
        <h1>Bem-vindo ao nosso sistema!</h1>
        <p></p>

       
        <a href="{{ route('produtos.index') }}" class="btn-large">CADASTRAR PRODUTOS</a>
    </div>
</body>
</html>
