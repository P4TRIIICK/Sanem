<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Telefone extends Model
{
    protected $table = 'telefone';
    public $timestamps = false;
    public $incrementing = false; // PK composta
    protected $primaryKey = null;
    protected $fillable = ['pessoa_id', 'numero'];

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class, 'pessoa_id');
    }
}
