<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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
            'password' => 'hashed',
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
    
    public function beneficiario(): HasOne
    {
        return $this->hasOne(Beneficiario::class, 'pessoa_id');
    }

    /**
     * NOVO: Cria um atributo virtual 'formatted_cpf' que formata o CPF.
     * Isto permite que você use {{ $pessoa->formatted_cpf }} nas suas views
     * para mostrar o CPF com o formato XXX.XXX.XXX-XX.
     */
    protected function formattedCpf(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) =>
                preg_replace(
                    "/(\d{3})(\d{3})(\d{3})(\d{2})/",
                    "$1.$2.$3-$4",
                    $attributes['cpf']
                )
        );
    }
}
