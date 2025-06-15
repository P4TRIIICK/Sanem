<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemDoacao extends Model
{
    protected $table = 'item_doacao';
    public $timestamps = false;
    public $incrementing = false; // PK composta
    protected $primaryKey = null;
    protected $fillable = [
        'doacao_id',
        'produto_id',
        'quantidade'
    ];

    public function doacao()
    {
        return $this->belongsTo(Doacao::class, 'doacao_id');
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }
}
