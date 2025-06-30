<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Doacao extends Model
{
    use HasFactory;

    /**
     * A tabela associada com o modelo.
     *
     * @var string
     */
    protected $table = 'doacao';

    /**
     * Indica se o modelo deve ter timestamps (created_at, updated_at).
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Os atributos que podem ser atribuídos em massa.
     *
     * @var array
     */
    protected $fillable = [
        'pessoa_id',
        'data_doacao',
        'instante',
        'status_doacao',
        'status_entrega',
    ];

    /**
     * Converte atributos para tipos nativos.
     *
     * @return array
     */
    protected function casts(): array
    {
        return [
            'data_doacao' => 'date',
        ];
    }

   
    public function doador(): BelongsTo
    {
        return $this->belongsTo(Pessoa::class, 'pessoa_id');
    }

    
    
    public function itens(): HasMany
    {
        return $this->hasMany(ItemDoacao::class, 'doacao_id');
    }
}
