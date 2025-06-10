<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Beneficiario extends Model
{
    protected $table = 'beneficiario';
    public $timestamps = false;
    public $incrementing = false; // PK = id já vem de pessoa
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'limite',
        'cartao_benef',
        'status_conta'
    ];

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class, 'id');
    }
}
