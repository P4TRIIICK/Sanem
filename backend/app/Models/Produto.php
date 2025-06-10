<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $table = 'produto';
    public $timestamps = false;
    protected $fillable = ['nome', 'qualidade'];

    public function categorias()
    {
        return $this->belongsToMany(
            Categoria::class,
            'categoria_produto',
            'produto_id',
            'categoria_id'
        );
    }

    public function itensDoacao()
    {
        return $this->hasMany(ItemDoacao::class, 'produto_id');
    }
}
