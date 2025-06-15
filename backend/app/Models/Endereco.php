<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Endereco extends Model
{
    protected $table = 'endereco';
    public $timestamps = false;
    protected $fillable = [
        'logradouro', 'numero', 'complemento', 'bairro', 'cep', 'cidade_id'
    ];

    public function cidade()
    {
        return $this->belongsTo(Cidade::class, 'cidade_id');
    }

    public function pessoas()
    {
        return $this->hasMany(Pessoa::class, 'endereco_id');
    }
}
