<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface RepositoryInterface
{
  public static function new(array $attributes): Model | null;
  
  public static function all(): Collection;

  public static function count(): int | null;

  public static function updater(Request $request, int $id): bool;

  public static function excluir(int $id): bool;

  public static function findById(int $id): Model | null;

  public static function loadModel(): Model;
}
