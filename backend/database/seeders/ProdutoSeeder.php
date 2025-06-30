<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produto;

class ProdutoSeeder extends Seeder
{
    /**
     * Popula a tabela de produtos com dados de teste.
     */
    public function run(): void
    {
        Produto::firstOrCreate(['nome' => 'Calça Jeans Adulto'], ['quantidade' => 0, 'tipo_produto' => 'ROUPA']);
        Produto::firstOrCreate(['nome' => 'Camiseta Adulto'], ['quantidade' => 0, 'tipo_produto' => 'ROUPA']);
        Produto::firstOrCreate(['nome' => 'Tênis Infantil'], ['quantidade' => 0, 'tipo_produto' => 'CALCADO']);
        Produto::firstOrCreate(['nome' => 'Prato de Jantar'], ['quantidade' => 0, 'tipo_produto' => 'UTENSILIO']);
        Produto::firstOrCreate(['nome' => 'Cobertor Solteiro'], ['quantidade' => 0, 'tipo_produto' => 'ROUPA_DE_CAMA']);
    }
}
