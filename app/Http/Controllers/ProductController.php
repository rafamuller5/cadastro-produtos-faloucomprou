<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Illuminate\Support\Collection;

class ProductController extends Controller
{
    protected $database;

    public function __construct()
    {
        // Conexão com o Firebase
        $firebase = (new Factory)
            ->withServiceAccount(storage_path('app/faloucomproucerto-firebase.json'))
            ->withDatabaseUri('https://faloucomproucerto-default-rtdb.firebaseio.com/');
    
        $this->database = $firebase->createDatabase();
    }

    public function index(Request $request)
    {
        // Obtém todos os produtos
        $produtos = $this->database->getReference('produtos')->getValue();

        // Verifica se há produtos
        if ($produtos) {
            // Filtragem por nome ou código de barras
            if ($request->has('search') && $request->search != '') {
                $search = strtolower($request->search);
                $produtos = array_filter($produtos, function ($produto) use ($search) {
                    return strpos(strtolower($produto['nome']), $search) !== false ||
                           strpos(strtolower($produto['codigoBarras']), $search) !== false;
                });
            }

            // Ordenação por preço
            if ($request->has('price_filter') && $request->price_filter != '') {
                $produtos = collect($produtos)->sortBy(function ($produto) {
                    return $produto['preco'];
                });

                if ($request->price_filter == 'desc') {
                    $produtos = $produtos->reverse();
                }

                $produtos = $produtos->toArray();
            }
        }

        // Retorna a view com os produtos filtrados
        return view('produtos.index', compact('produtos'));
    }

    public function create()
    {
        return view('produtos.create');
    }

    public function store(Request $request)
{
    // Valida os dados
    $request->validate([
        'codigoBarras' => 'required',
        'imagemUrl' => 'required|url',
        'nome' => 'required|string',
        'preco' => 'required|numeric',
    ]);

    // Obtém a lista de produtos para determinar o próximo ID
    $produtos = $this->database->getReference('produtos')->getValue();
    $nextId = $produtos ? count($produtos) + 1 : 1;

    // Define os dados do produto, usando o nome como chave
    $produtoData = [
        'id' => (string) $nextId,
        'nome' => $request->nome,
        'preco' => $request->preco,
        'imagemUrl' => $request->imagemUrl,
        'codigoBarras' => $request->codigoBarras,
    ];

    // Salva o produto no Firebase com o nome como chave
    $this->database->getReference('produtos/' . $request->nome)->set($produtoData);

    // Redireciona após o produto ser adicionado
    return redirect()->route('produtos.index')->with('success', 'Produto adicionado com sucesso!');
}


    public function edit($id)
    {
        // Obtém o produto do Firebase
        $produto = $this->database->getReference('produtos/' . $id)->getValue();

        // Verifica se o produto existe
        if (!$produto) {
            return redirect()->route('produtos.index')->with('error', 'Produto não encontrado.');
        }

        // Retorna a view de edição com os dados do produto
        return view('produtos.edit', compact('produto', 'id'));
    }

    public function update(Request $request, $id)
    {
        // Valida os dados
        $request->validate([
            'nome' => 'required|string',
            'preco' => 'required|numeric',
            'imagemUrl' => 'required|url',
            'codigoBarras' => 'required',
        ]);

        // Atualiza o produto no Firebase
        $this->database->getReference('produtos/' . $id)->update([
            'nome' => $request->nome,
            'preco' => $request->preco,
            'imagemUrl' => $request->imagemUrl,
            'codigoBarras' => $request->codigoBarras,
        ]);

        // Redireciona após o produto ser atualizado
        return redirect()->route('produtos.index')->with('success', 'Produto atualizado com sucesso!');
    }

    public function destroy($id)
    {
        // Remove o produto do Firebase
        $this->database->getReference('produtos/' . $id)->remove();
    
        // Redireciona após o produto ser excluído
        return redirect()->route('produtos.index')->with('success', 'Produto excluído com sucesso!');
    }

    public function importar(Request $request)
{
    // Validação do arquivo CSV
    $request->validate([
        'csv_file' => 'required|mimes:csv,txt|max:2048',
    ]);

    // Carrega o arquivo CSV
    $file = $request->file('csv_file');
    $filePath = $file->getRealPath();

    // Abre o arquivo e lê o conteúdo
    $file = fopen($filePath, 'r');
    $header = fgetcsv($file); // Lê o cabeçalho (primeira linha)

    // Pega a lista atual de produtos para definir o próximo ID
    $produtosExistentes = $this->database->getReference('produtos')->getValue();
    $nextId = $produtosExistentes ? count($produtosExistentes) + 1 : 1;

    // Processa cada linha do arquivo
    while ($row = fgetcsv($file)) {
        $data = array_combine($header, $row); // Combina o cabeçalho com os dados da linha

        // Define os dados do produto, incluindo o nome
        $produtoData = [
            'id' => (string) $nextId,
            'codigoBarras' => $data['codigoBarras'],
            'imagemUrl' => $data['imagemUrl'],
            'nome' => $data['nome'], // Adiciona o nome ao próprio registro
            'preco' => $data['preco'],
        ];

        // Salva o produto no Firebase com o nome como chave
        $this->database->getReference('produtos/' . $data['nome'])->set($produtoData);

        // Incrementa o ID para o próximo produto
        $nextId++;
    }

    fclose($file);

    // Redireciona após importar os produtos
    return redirect()->back()->with('success', 'Produtos importados com sucesso!');
}


}
