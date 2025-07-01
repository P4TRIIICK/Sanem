<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Doacao extends Model
{
    use HasFactory;

    /**
     * CORREÇÃO: Especifica o nome correto da tabela.
     *
     * @var string
     */
    protected $table = 'doacoes';

    /**
     * Os atributos que podem ser atribuídos em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pessoa_id',
        'funcionario_id',
        'data_doacao',
    ];

    /**
     * Define o relacionamento com o beneficiário (Pessoa que recebeu).
     */
    public function beneficiario(): BelongsTo
    {
        return $this->belongsTo(Pessoa::class, 'pessoa_id');
    }

    /**
     * Define o relacionamento com o funcionário (Pessoa que registou).
     */
    public function funcionario(): BelongsTo
    {
        return $this->belongsTo(Pessoa::class, 'funcionario_id');
    }

    /**
     * Define o relacionamento de muitos-para-muitos com os Itens.
     */
    public function itens(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'doacao_item', 'doacao_id', 'item_id')
                    ->withPivot('quantidade_doada')
                    ->withTimestamps();
    }
}
