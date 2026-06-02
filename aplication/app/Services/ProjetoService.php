<?php

namespace App\Services;

use App\Repository\ProjetoRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ProjetoService
{

  public function __construct(
    protected ProjetoRepository $projetoRepository
  ) {}

  public function all(string $filter = null): Collection
  {
    return $this->projetoRepository->all($filter);
  }
  public function count(): int | null
  {
    return $this->projetoRepository->count();
  }

  public function findByName(string $nome = null): Collection
  {
    return $this->projetoRepository->findByName($nome);
  }

  public function new(array $attributes = [])
  {
    return $this->projetoRepository->new($attributes);
  }

  public function findById(int $id = null): Model | null
  {
    return $this->projetoRepository->findById($id);
  }

  public function excluir(int $id = null): bool
  {
    return $this->projetoRepository->excluir($id);
  }

  public function updater(Request $request = null, int $id = null): bool
  {
    return $this->projetoRepository->updater($request, $id);
  }
}
