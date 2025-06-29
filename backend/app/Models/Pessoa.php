<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Pessoa extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    protected $table = 'pessoa';
    public $timestamps = false;
    
    /**
     * Força o modelo a usar o guarda 'web' por padrão para o Spatie.
     * Isso resolve o conflito de permissão 403 após o login web.
     */
    protected $guard_name = 'web';

    /**
     * Os atributos que podem ser atribuídos em massa.
     */
    protected $fillable = [
        'nome',
        'cpf',
        'rg',
        'genero',
        'tipo_beneficiario',
        'nascimento',
        'email',
        'password',
        'endereco_id'
    ];

    /**
     * Os atributos que devem ser ocultados para serialização.
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Os atributos que devem ser convertidos.
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed', // Garante que a senha sempre seja criptografada
        ];
    }

    // --- Relacionamentos ---
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

    /**
     * Define o relacionamento com Beneficiario.
     * Uma Pessoa pode ter um registro de Beneficiario.
     * CORREÇÃO: A chave estrangeira na tabela 'beneficiarios' é 'pessoa_id'.
     */
    public function beneficiario(): HasOne
    {
        return $this->hasOne(Beneficiario::class, 'pessoa_id');
    }
}
