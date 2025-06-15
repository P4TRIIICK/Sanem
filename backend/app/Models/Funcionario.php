<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Funcionario extends Model
{
    protected $table = 'funcionario';
    public $timestamps = false;
    public $incrementing = false; // PK = id já vem de pessoa
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'nivel_acesso',
        'salario',
        'data_contratacao'
    ];

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class, 'id');
    }

    public function relatorios()
    {
        return $this->hasMany(Relatorio::class, 'funcionario_id');
    }
}
