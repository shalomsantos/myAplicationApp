<?php

declare(strings_type=1);

namespace App\Repository;

use App\Models\Bzero;
use Illuminate\Support\Collection;

class BzeroRepository extends AbstractRepository
{
  protected static $model = Bzero::class;

  public static function findByName(string $nome): Collection
  {
    return Bzero::query()->where([['nome', 'like', '%' . $nome . '%']])->get();
  }
}
