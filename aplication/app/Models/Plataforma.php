<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

class Plataforma extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'descricao',
        'updated_by'
    ];
    protected $with = ['createdBy', 'updatedBy'];

    public function itens(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'plataforma_template', 'plataforma_id', 'item_id');
    }

    public function itensPivot(): HasMany
{
    return $this->hasMany(PlataformaTemplate::class, 'plataforma_id');
}

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
