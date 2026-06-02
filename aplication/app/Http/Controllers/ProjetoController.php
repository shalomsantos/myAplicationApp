<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Projeto;
use App\Models\TipoProjeto;

class ProjetoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Projeto::with('tipoProjeto')->orderBy('id', 'desc');
        $tipoProjeto = TipoProjeto::orderBy('id', 'desc')->select(['id', 'nome']);

        if ($request->filled('search')) $query->where('nome', 'like', "%{$request->search}%");

        if ($request->expectsJson()) return response()->json($query->paginate(6));

        try {
            $usuario_logado = auth()->user();
            $preferencias = $usuario_logado->preferencia;

            return Inertia::render('Crud/cadastros/projetos/index', [
            'projetos' => $query->paginate(6),
            'tiposProjetos' => $tipoProjeto->get(),
            'user' => $usuario_logado,
            'preferencias' => $preferencias,
        ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $projetoStore = Projeto::insert([
                'nome' => $request['nome'],
                'tipo_projeto_id' => $request['tipo_projeto_id'],
                'descricao' => $request['descricao'] ?? '',
                'created_by' => auth()->id(),
                // 'updated_by' => ,
                'created_at' => now(),
                // 'updated_at' =>
            ]);
            if ($projetoStore) {
                return response()->json([
                    'success' => true,
                    'message' => "Projeto: " . $request['nome'] . ", criado com sucesso."
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => "Houve um erro no procedimento de inserção de registro."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Erro ao tentar inserir o projeto: " . $request['nome'] . ".",
                'details' => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'nome' => 'required|string|max:255',
                'tipo_projeto_id' => 'required|integer|exists:tipo_projetos,id',
            ]);

            $projeto = Projeto::find($id);

            if (!$projeto) {
                return response()->json([
                    'success' => false,
                    'message' => "Projeto com ID: {$id} não encontrado."
                ], 404);
            }

            $projetoAtualizado = $projeto->update([
                'nome' => $request['nome'],
                'tipo_projeto_id' => $request['tipo_projeto_id'],
                'descricao' => $request['descricao'] ?? '',
                'updated_by' => auth()->id(),
                'updated_at' => now(),
            ]);
            if ($projetoAtualizado) {
                return response()->json([
                    'success' => true,
                    'message' => "Projeto: " . $request['nome'] . ", atualizado com sucesso."
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => "Nenhuma alteração detectada no projeto."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Erro ao tentar atualizar o projeto: " . $request['nome'] . ".",
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
