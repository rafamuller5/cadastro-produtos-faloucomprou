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

        <!-- Botão de upload de CSV -->
        <form action="{{ route('produtos.importar') }}" method="POST" enctype="multipart/form-data" style="margin-bottom: 20px;">
            @csrf
            <div class="form-group">
                <label for="csv_file">Importar Produtos via CSV:</label>
                <input type="file" name="csv_file" id="csv_file" accept=".csv" required>
            </div>
            <button type="submit" class="btn btn-primary">Importar CSV</button>
        </form>

        <!-- Filtro de Produtos -->
        <form action="{{ route('produtos.index') }}" method="GET" class="filter-form">
            <input type="text" name="search" placeholder="Pesquisar por nome ou código de barras" value="{{ request('search') }}">
            <select name="price_filter">
                <option value="">Filtrar por preço</option>
                <option value="asc" {{ request('price_filter') == 'asc' ? 'selected' : '' }}>Menor Preço</option>
                <option value="desc" {{ request('price_filter') == 'desc' ? 'selected' : '' }}>Maior Preço</option>
            </select>
            <button type="submit" class="btn">Filtrar</button>
        </form>

        <!-- Lista de Produtos -->
        <div class="product-list">
            @if ($produtos && count($produtos) > 0)
                @foreach ($produtos as $idProduto => $produto)
                    <div class="product-card">
                        <img src="{{ $produto['imagemUrl'] ?? 'default-image-url.png' }}" alt="{{ $produto['nome'] ?? 'Produto sem nome' }}" class="product-image">
                        <div class="product-info">
                            <h2>{{ $produto['nome'] ?? 'Nome não disponível' }}</h2>
                            <p><strong>Código de Barras:</strong> {{ $produto['codigoBarras'] ?? 'Código não disponível' }}</p>
                            <p><strong>Preço:</strong> {{ isset($produto['preco']) ? 'R$ ' . number_format($produto['preco'], 2, ',', '.') : 'Preço não disponível' }}</p>
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
            @else
                <p>Nenhum produto encontrado.</p>
            @endif
        </div>
    </div>
</body>
</html>
