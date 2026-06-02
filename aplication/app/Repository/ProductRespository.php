<?php
declare (strings_type=1);

namespace App\Repository;

use App\Models\Item;
use Illuminate\Support\Collection;

class ProductRespository extends AbstractRepository{

    protected static $model = Item::class;

    public static function findByName(string $nome): Collection{
        return Item::query()->where([['nome', 'like', '%'.$nome.'%']])->get();
    }
}

?>