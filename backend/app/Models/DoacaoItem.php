<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoacaoItem extends Model
{
    use HasFactory;

    /**
     * O nome da tabela associada com o modelo.
     *
     * @var string
     */
    protected $table = 'doacao_item';

    /**
     * Os atributos que podem ser atribuídos em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'doacao_id',
        'item_id',
        'quantidade_doada',
    ];
}
