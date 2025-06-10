<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cidade extends Model
{
    protected $table = 'cidade';
    public $timestamps = false;
    protected $fillable = ['nome', 'estado_id'];

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }

    public function enderecos()
    {
        return $this->hasMany(Endereco::class, 'cidade_id');
    }
}
