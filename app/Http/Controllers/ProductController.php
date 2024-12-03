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
        $firebase = (new Factory)
            ->withServiceAccount(storage_path('app/faloucomproucerto-firebase.json'))
            ->withDatabaseUri('https://faloucomprou-default-rtdb.firebaseio.com/');

        $this->database = $firebase->createDatabase();
    }

    public function index(Request $request)
    {
        $produtos = $this->database->getReference('produtos')->getValue();

        if ($produtos) {
            if ($request->has('search') && $request->search != '') {
                $search = strtolower($request->search);
                $produtos = array_filter($produtos, function ($produto) use ($search) {
                    return strpos(strtolower($produto['nome']), $search) !== false ||
                           strpos(strtolower($produto['codigoBarras']), $search) !== false;
                });
            }

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

        return view('produtos.index', compact('produtos'));
    }

    public function create()
    {
        return view('produtos.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'codigoBarras' => 'required',
        'imagemUrl' => 'required|url',
        'nome' => 'required|string',
        'preco' => 'required|numeric',
    ]);

    $produtos = $this->database->getReference('produtos')->getValue();
    $nextId = $produtos ? count($produtos) + 1 : 1;

    $produtoData = [
        'id' => (string) $nextId,
        'nome' => $request->nome,
        'preco' => (float) $request->preco, // Convertendo para float
        'imagemUrl' => $request->imagemUrl,
        'codigoBarras' => $request->codigoBarras,
    ];

    $this->database->getReference('produtos/' . $nextId)->set($produtoData);

    return redirect()->route('produtos.index')->with('success', 'Produto adicionado com sucesso!');
}


    public function edit($id)
    {
        $produto = $this->database->getReference('produtos/' . $id)->getValue();

        if (!$produto) {
            return redirect()->route('produtos.index')->with('error', 'Produto não encontrado.');
        }

        return view('produtos.edit', compact('produto', 'id'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nome' => 'required|string',
            'preco' => 'required|numeric',
            'imagemUrl' => 'required|url',
            'codigoBarras' => 'required',
        ]);

        $this->database->getReference('produtos/' . $id)->update([
            'nome' => $request->nome,
            'preco' => $request->preco,
            'imagemUrl' => $request->imagemUrl,
            'codigoBarras' => $request->codigoBarras,
        ]);

        return redirect()->route('produtos.index')->with('success', 'Produto atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $this->database->getReference('produtos/' . $id)->remove();

        return redirect()->route('produtos.index')->with('success', 'Produto excluído com sucesso!');
    }

    public function importar(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('csv_file');
        $filePath = $file->getRealPath();

        $file = fopen($filePath, 'r');
        $header = fgetcsv($file); 

        $produtosExistentes = $this->database->getReference('produtos')->getValue();
        $nextId = $produtosExistentes ? count($produtosExistentes) + 1 : 1;

        while ($row = fgetcsv($file)) {
            $data = array_combine($header, $row); 

            $produtoData = [
                'id' => (string) $nextId,
                'codigoBarras' => $data['codigoBarras'],
                'imagemUrl' => $data['imagemUrl'],
                'nome' => $data['nome'], 
                'preco' => (float) $data['preco'], // Convertendo para float
            ];


            $this->database->getReference('produtos/' . $nextId)->set($produtoData);


            $nextId++;
        }

        fclose($file);

        return redirect()->route('produtos.index')->with('success', 'Produtos importados com sucesso!');
    }

    public function showImportForm()
    {
        return view('produtos.importar');
    }
}
