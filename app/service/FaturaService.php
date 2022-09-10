<?php

namespace App\service;

use App\Models\Cartao;
use App\Models\Fatura;
use App\repository\FaturaRepository;
use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class FaturaService {

    private $faturaRepository;

    function __construct() {
        $this->faturaRepository = new FaturaRepository();
    }

    public function criarObjectFatura($valor, $data, $isFechada, $isPaga, $cartao, $id_cartao_data): Fatura {
        $fatura = new Fatura();
        $fatura->valor = $valor;
        $fatura->data = $data;
        $fatura->isFechada = $isFechada;
        $fatura->isPaga = $isPaga;
        $fatura->id_cartao = $cartao;
        $fatura->id_cartao_data = $id_cartao_data;
        return $fatura;
    }

    public function buscarFaturas():Collection{
        return $this->faturaRepository->buscarTodasFaturas();
    }

    public function criarFatura(Request $request):Array {
        $fatura = new Fatura($request);
        return $this->faturaRepository->criarFatura($fatura);
    }

    public function criarFaturaMes() {
        $cartoes = Cartao::all('*');
        $data = new DateTime();
        $dadosTime = getdate($data->getTimestamp());
        $ano = $dadosTime['year'];
        $mes = $dadosTime['mon'];
        $day = $dadosTime['mday'];
        foreach ($cartoes as $cartao) {
            $cartao_data = "$cartao->id$mes$ano";
            $fatura = Fatura::where('id_cartao_data', $cartao_data)->first();
            if (is_null($fatura)) {
                $nova_fatura = FaturaService::criarObjectFatura(
                    0.0,
                    new DateTime("$ano-$mes-$cartao->dia_vencimento"),
                    0,
                    0,
                    $cartao->id,
                    $cartao_data
                );
                $nova_fatura->save();
            }
        }
    }
}
