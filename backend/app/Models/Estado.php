<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    protected $table = 'estado';
    public $timestamps = false;
    protected $fillable = ['nome'];

    public function cidades()
    {
        return $this->hasMany(Cidade::class, 'estado_id');
    }
}
