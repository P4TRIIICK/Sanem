<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Pessoa extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'pessoa';
    public $timestamps = false;

    protected $fillable = [
        'nome',
        'cpf',
        'rg',
        'genero',
        'tipo_beneficiario',
        'nascimento',
        'email',
        'password',      // já incluído
        'endereco_id'
    ];

    protected $hidden = [
        'password',
        'remember_token', // se tiver habilitado
    ];

    // Relacionamentos…

    public function endereco()
    {
        return $this->belongsTo(Endereco::class, 'endereco_id');
    }

    public function telefones()
    {
        return $this->hasMany(Telefone::class, 'pessoa_id');
    }

    public function doacoes()
    {
        return $this->hasMany(Doacao::class, 'pessoa_id');
    }

    public function funcionario()
    {
        return $this->hasOne(Funcionario::class, 'id');
    }

    public function beneficiario()
    {
        return $this->hasOne(Beneficiario::class, 'id');
    }
}
