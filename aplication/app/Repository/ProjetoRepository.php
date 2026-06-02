<?php

declare(strings_type=1);

namespace App\Repository;

use App\Models\Projeto;
use Illuminate\Support\Collection;

class ProjetoRepository extends AbstractRepository
{
  protected static $model = Projeto::class;

  public static function findByName(string $nome): Collection
  {
    return Projeto::query()->where([['nome', 'like', '%' . $nome . '%']])->get();
  }
}
