<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Relatorio extends Model
{
    protected $table = 'relatorio';
    public $timestamps = false;
    protected $fillable = [
        'data_relatorio',
        'formato',
        'tipo_relatorio',
        'descricao',
        'funcionario_id'
    ];

    public function funcionario()
    {
        return $this->belongsTo(Funcionario::class, 'funcionario_id');
    }
}
