<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doacao extends Model
{
    protected $table = 'doacao';
    public $timestamps = false;
    protected $fillable = [
        'instante',
        'status_doacao',
        'pessoa_id'
    ];

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class, 'pessoa_id');
    }

    public function itens()
    {
        return $this->hasMany(ItemDoacao::class, 'doacao_id');
    }
}
