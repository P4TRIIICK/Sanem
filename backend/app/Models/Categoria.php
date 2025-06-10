<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categoria';
    public $timestamps = false;
    protected $fillable = ['nome'];

    public function produtos()
    {
        return $this->belongsToMany(
            Produto::class,
            'categoria_produto',
            'categoria_id',
            'produto_id'
        );
    }
}
