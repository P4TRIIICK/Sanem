<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Beneficiario extends Model
{
    use HasFactory;

    /**
     * O nome da tabela associada com o model.
     *
     * @var string
     */
    protected $table = 'beneficiarios';

    /**
     * Os atributos que podem ser atribuídos em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pessoa_id',
        'identificador_unico',
        'renda',
        'foto_path',
        'status',
    ];

    /**
     * Define o relacionamento inverso com Pessoa.
     * Um registro de Beneficiario pertence a uma Pessoa.
     */
    public function pessoa(): BelongsTo
    {
        return $this->belongsTo(Pessoa::class, 'pessoa_id');
    }
}
