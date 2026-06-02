<?php

namespace App\Http\Controllers;

use App\Models\Fornecedor;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FornecedorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 6);

        $query = Fornecedor::query();

        if ($request->filled('search')) {
            $query->where('razao_social', 'like', "%{$request->search}%")
                ->orWhere('cnpj', 'like', "%{$request->search}%");
        }
        $fornecedores = $query->orderBy('id', 'desc')->paginate($perPage);

        if ($request->expectsJson()) return $fornecedores;

        try {
            $usuario_logado = auth()->user();
            $preferencias = $usuario_logado->preferencia;

            return Inertia::render('Crud/cadastros/fornecedores/index', [
                'fornecedores' => $fornecedores,
                'user'         => $usuario_logado,
                'preferencias' => $preferencias
            ]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
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
            $bzeroStore = Fornecedor::insert([
                'nome_fantasia' => $request->input('nome_fantasia') ?? null,
                'razao_social'  => $request->input('razao_social') ?? null,
                'descricao'     => $request->input('descricao') ?? null,
                'cpf'           => $request->input('cpf') ?? null,
                'cnpj'          => $request->input('cnpj') ?? null,
                'endereco'      => $request->input('endereco') ?? null,
                'contato'       => $request->input('contato') ?? null,
                'email'         => $request->input('email') ?? null,
                'created_by'    => auth()->id(),
                'created_at'    => now(),
            ]);
            if ($bzeroStore) {
                return response()->json([
                    'success' => true,
                    'message' => "Registro criado com sucesso."
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => "Houve um erro no procedimento de inserção de registro."
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => "Erro ao tentar inserir a base.",
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
