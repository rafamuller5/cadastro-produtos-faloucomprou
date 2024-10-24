<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;

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

    public function index()
    {
       
        $produtos = $this->database->getReference('produtos')->getValue();
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
    
       
        $novoProdutoReferencia = $this->database->getReference('produtos')->push();
    
       
        $novoProdutoReferencia->set([
            'codigoBarras' => $request->codigoBarras,
            'id' => $novoProdutoReferencia->getKey(), 
            'imagemUrl' => $request->imagemUrl,
            'nome' => $request->nome,
            'preco' => $request->preco,
        ]);
    
        
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
}
