<div class="import-page">
    <div class="container">
        <h1>Importar Produtos</h1>
        <link rel="stylesheet" href="{{ asset('css/produtos.css') }}">

        <a href="{{ asset('planilha_exemplo.csv') }}" class="btn btn-download" download>Baixar Planilha de Exemplo</a>

        <form action="{{ route('produtos.importar.post') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="csv_file">Selecione o arquivo CSV:</label>
                <input type="file" name="csv_file" id="csv_file" class="form-control" required>
            </div>

            <div class="actions">
                <button type="submit" class="btn btn-secondary">Importar Produtos</button>
                <a href="{{ route('produtos.index') }}" class="btn btn-danger">Cancelar</a>
            </div>
        </form>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
