<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Produto</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="form-page">
    <div class="form-container">
        <h1>Adicionar Novo Produto</h1>

        <form action="{{ route('produtos.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="codigoBarras">Código de Barras:</label>
                <input type="text" name="codigoBarras" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" name="nome" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="imagemUrl">URL da Imagem:</label>
                <input type="url" name="imagemUrl" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="preco">Preço:</label>
                <input type="number" name="preco" class="form-control" step="0.01" required>
            </div>

            <button type="submit" class="btn-submit">Salvar Produto</button>
        </form>

        <a href="{{ route('produtos.index') }}" class="btn-back">Voltar à lista de produtos</a>
    </div>
</body>
</html>
