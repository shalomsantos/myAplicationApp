<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\Preferencia;
use App\Models\Bzero;
use App\Models\BzeroPlataforma;
use App\Models\Plataforma;
use App\Models\Projeto;
use App\Services\ProjetoService;

class BzeroController extends Controller
{
    public function __construct(
        protected ProjetoService $projetoService
    ) {}
    /**
     * Filtra registros de Bzero conforme parâmetros recebidos.
     */
    public function filtro(Request $request)
    {
        $result = Bzero::query()
            ->when($request->id, fn($q) => $q->where('id', $request->id))
            ->when($request->projeto_id, fn($q) => $q->where('projeto_id', $request->projeto_id))
            ->when($request->ano, fn($q) => $q->where('ano', $request->ano))
            ->when($request->status_id, fn($q) => $q->where('status_id', $request->status_id))
            ->when($request->created_at_start, function ($q) use ($request) {
                $start = \Carbon\Carbon::parse($request->created_at_start)->startOfDay();
                $end = $request->created_at_end
                    ? \Carbon\Carbon::parse($request->created_at_end)->endOfDay()
                    : $start->copy()->endOfDay();
                $q->whereBetween('created_at', [$start, $end]);
            })
            ->latest() 
            ->paginate(6) 
            ->withQueryString(); 

        if ($result->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Nenhum registro encontrado.',
                'data' => $result 
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Registros processados com sucesso.',
            'data' => $result
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Bzero::with('plataformas')->orderBy('id', 'desc');
            $projetos = Projeto::orderBy('id', 'desc');

            if ($request->expectsJson()) return response()->json($query->paginate(6));

            $usuario_logado = auth()->user();
            $preferencias = $usuario_logado->preferencia;

            return Inertia::render('Dashboard', [
                'bzeros' => $query->paginate(6),
                'projetos' => $projetos->get(),
                'preferencias' => $preferencias
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
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
            $bzeroStore = Bzero::insert([
                'projeto_id' => $request->input('projeto_id'),
                'ano' => $request->input('ano'),
                'descricao' => $request->input('descricao') ?? '',
                'dt_aprovacao' => null,
                'created_by' => auth()->id(),
                // 'updated_by' => ,
                'created_at' => now(),
                // 'updated_at' =>
            ]);
            if ($bzeroStore) {
                return response()->json([
                    'success' => true,
                    'message' => "base criada com sucesso."
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
        $bzero = Bzero::with(['plataformas.itensPivot'])->findOrFail($id);

        $usuario_logado = auth()->user();
        $preferencias = $usuario_logado->preferencia;

        return Inertia::render('Crud/basezero/EditeBzero', [
            'bzero' => $bzero,
            'plataformas' => Plataforma::all(),
            'preferencias' => $preferencias
        ]);
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
        try {
            $ifDestroy = Bzero::destroy($id);
            if($ifDestroy){
                return response()->json([
                    'success' => true,
                    'message' => 'Operação realizada com sucesso.',
                ], 200);
            }
            return response()->json([
                'success' => false,
                'message' => 'Houve algo problema ao tentar deletar',
            ], 500);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function associarPLataforma(string $id, Request $request) 
    {
        try {
            $associado = BzeroPlataforma::insert([
                'bzero_id' => $id,
                'plataforma_id' => $request->input('plataforma_id')
            ]);
            if ($associado) {
                $data = Bzero::with(['plataformas.itensPivot'])->findOrFail($id);
                return response()->json([
                    'success' => true,
                    'message' => 'Associado com sucesso!',
                    'data' => $data
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => "Houve um erro no procedimento de associação de registro."
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => "Erro ao tentar associar a plataforma a bzero.",
                'details' => $e->getMessage()
            ]);
        }
    }

    public function modify(Request $request)
    {
        try {
            $preferencia = Preferencia::updateOrCreate([
                'user_id' => $request->input('user_id')
            ], [
                'listagem_menu' => $request->input('listagem_menu')
            ]);

            if ($preferencia) {
                return response()->json([
                    'success' => true,
                    'message' => "Preferência modificada/criada com sucesso."
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => "Houve um erro ao tentar salvar a preferência."
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => "Erro ao tentar modificar/criar a preferência.",
                'details' => $e->getMessage()
            ]);
        }
    }
}
