<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidarCpf implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // 1. Remove qualquer formatação (pontos, traços)
        $cpf = preg_replace('/[^0-9]/', '', (string) $value);

        // 2. Verifica se tem 11 dígitos e se não são todos iguais
        if (strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // 3. Calcula os dígitos verificadores para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'O CPF fornecido não é válido.';
    }
}
