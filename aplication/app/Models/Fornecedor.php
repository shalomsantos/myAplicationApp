<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Fornecedor extends Model
{
    protected $table = 'fornecedores';

    use HasFactory;

    protected $fillable = [
        'nome_fantasia',
        'razao_social',
        'cpf',
        'cnpj',
        'endereco',
        'contato',
        'email',
        'descricao',
        'updated_by',
    ];

    protected $with = ['createdBy', 'updatedBy'];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
