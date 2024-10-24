<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Produtos</title>
    <link rel="stylesheet" href="{{ asset('css/produtos.css') }}">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Lista de Produtos</h1>
            <a href="{{ route('produtos.create') }}" class="btn btn-primary">Adicionar Novo Produto</a>
        </div>

        <div class="product-list">
            @foreach ($produtos as $idProduto => $produto)
                <div class="product-card">
                    <img src="{{ $produto['imagemUrl'] ?? 'default-image-url.png' }}" alt="{{ $produto['nome'] ?? 'Produto sem nome' }}" class="product-image">
                    <div class="product-info">
                        <h2>{{ $produto['nome'] ?? 'Nome não disponível' }}</h2>
                        <p><strong>Código de Barras:</strong> {{ $produto['codigoBarras'] ?? 'Código não disponível' }}</p>
                        <p><strong>Preço:</strong> {{ isset($produto['preco']) ? number_format($produto['preco'], 2, ',', '.') . ' R$' : 'Preço não disponível' }}</p>
                        <div class="actions">
                            <a href="{{ route('produtos.edit', $idProduto) }}" class="btn">Editar</a>
                            <form action="{{ route('produtos.destroy', $idProduto) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Excluir</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>
