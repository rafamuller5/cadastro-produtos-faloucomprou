<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="form-page">
    <div class="form-container">
        <h1>Editar Produto</h1>

        <form action="{{ route('produtos.update', $produto['id']) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="codigoBarras">Código de Barras:</label>
                <input type="text" name="codigoBarras" class="form-control" value="{{ $produto['codigoBarras'] }}" required>
            </div>

            <div class="form-group">
                <label for="nome">Nome do Produto:</label>
                <input type="text" name="nome" class="form-control" value="{{ $produto['nome'] }}" required>
            </div>

            <!-- <div class="form-group">
                <label for="id">ID:</label>
                <input type="text" name="id" class="form-control" value="{{ $produto['id'] }}" required readonly>
            </div> -->

            <div class="form-group">
                <label for="imagemUrl">URL da Imagem:</label>
                <input type="text" name="imagemUrl" class="form-control" value="{{ $produto['imagemUrl'] }}" required>
            </div>

            <div class="form-group">
                <label for="preco">Preço:</label>
                <input type="number" name="preco" class="form-control" value="{{ $produto['preco'] }}" step="0.01" required>
            </div>

            <button type="submit" class="btn-submit">Salvar Alterações</button>
            
        </form>
        <a href="{{ route('produtos.index') }}" class="btn-back">Cancelar</a>
    </div>
</body>
</html>
