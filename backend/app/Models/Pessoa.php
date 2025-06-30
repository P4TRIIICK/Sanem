<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
<<<<<<< Updated upstream
use Illuminate\Database\Eloquent\Relations\HasOne;
=======
>>>>>>> Stashed changes
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
<<<<<<< Updated upstream
=======
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
>>>>>>> Stashed changes

class Pessoa extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    /** @var string O nome da tabela. */
    protected $table = 'pessoa';
    
    /**
     * @var bool Desabilita a gestão automática das colunas 'created_at' e 'updated_at'.
     * ESSA LINHA CORRIGE O ERRO 'Unknown column 'updated_at''.
     */
    public $timestamps = false;
    
<<<<<<< Updated upstream
    /**
     * Força o modelo a usar o guarda 'web' por padrão para o Spatie.
     * Isso resolve o conflito de permissão 403 após o login web.
     */
    protected $guard_name = 'web';

    /**
     * Os atributos que podem ser atribuídos em massa.
     */
=======
    /** @var string Força o uso do 'guard' web para o Spatie/permission. */
    protected $guard_name = 'web';

    /** @var array<int, string> Atributos que podem ser atribuídos em massa. */
>>>>>>> Stashed changes
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

<<<<<<< Updated upstream
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
=======
    /** @var array<int, string> Atributos ocultos na serialização. */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /** @return array<string, string> Converte atributos para tipos nativos. */
    protected function casts(): array
>>>>>>> Stashed changes
    {
        return [
            'nascimento' => 'date',
            'password' => 'hashed',
        ];
    }
<<<<<<< Updated upstream

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
=======
    
    // --- Relacionamentos (sem alterações) ---
    public function endereco(): BelongsTo { /* ... */ }
    public function telefones(): HasMany { /* ... */ }
    public function doacoes(): HasMany { /* ... */ }
    public function funcionario(): HasOne { /* ... */ }
    public function beneficiario(): HasOne { /* ... */ }
}
>>>>>>> Stashed changes
