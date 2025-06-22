<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles; // <-- 1. IMPORTE O TRAIT DO SPATIE

class Pessoa extends Authenticatable
{
    // 2. ADICIONE O HasRoles AQUI PARA GANHAR OS "SUPERPODERES"
    use HasApiTokens, Notifiable, HasRoles;

    protected $table = 'pessoa';
    public $timestamps = false;

    /**
     * 3. Adicione esta linha para garantir que o Spatie funcione com Sanctum/API
     * (Opcional se o seu guarda padrão for 'sanctum', mas é uma boa prática ser explícito)
     */
    protected $guard_name = 'sanctum';


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