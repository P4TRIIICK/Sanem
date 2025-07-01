<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    /**
     * Os atributos que podem ser atribuídos em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
    'nome_item',
    'quantidade',
    'categoria_principal',
    'descricao',
    'foto_path',
    'detalhes',
    'status', // <-- ADICIONE ESTA LINHA
    ];

    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'detalhes' => 'array', // Converte automaticamente o JSON para um array PHP
    ];
}
