<?php


namespace App\Utils;

use App\Models\Fatura;

class FaturaUtils {

    public static function criarObjectFatura($valor, $data, $isFechada, $isPaga, $cartao, $id_cartao_data): Fatura {
        $fatura = new Fatura();
        $fatura->valor = $valor;
        $fatura->data = $data;
        $fatura->isFechada = $isFechada;
        $fatura->isPaga = $isPaga;
        $fatura->id_cartao = $cartao;
        $fatura->id_cartao_data = $id_cartao_data;
        return $fatura;
    }
}
