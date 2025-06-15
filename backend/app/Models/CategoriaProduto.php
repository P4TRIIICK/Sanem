<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaProduto extends Model
{
    protected $table = 'categoria_produto';
    public $timestamps = false;
    public $incrementing = false; // PK composta
    protected $primaryKey = null;
    protected $fillable = [
        'categoria_id',
        'produto_id'
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }
}
