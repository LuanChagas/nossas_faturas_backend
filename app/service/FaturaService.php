<?php

namespace App\service;

use App\Models\Cartao;
use App\Models\Fatura;
use App\repository\CartaoRepository;
use App\repository\FaturaRepository;
use App\Utils\FaturaUtils;
use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FaturaService {

    private $faturaRepository;
    private $cartaoRepository;

    function __construct() {
        $this->faturaRepository = new FaturaRepository();
        $this->cartaoRepository = new CartaoRepository();
    }

    public function buscarFaturas(): Response {
        $response = $this->faturaRepository->buscarTodasFaturas();
        if (is_array($response)) {
            return response(["mensagem" => $response["mensagem"]], $response["status"])
                ->header('Content-Type', 'application/problem');
        }
        return response($response, 200)->header('Content-Type', 'application/json');
    }

    public function criarFatura(Request $request): Response {
        $fatura = new Fatura($request);
        $response = $this->faturaRepository->criarFatura($fatura);
        $header = $response["status"] != 500 ? 'application/json' : 'application/problem';
        return response(["mensagem" => $response["mensagem"]], $response["status"])
            ->header('Content-Type', $header);
    }

    public function criarFaturaMes(): Response {

        $cartoes = $this->cartaoRepository->buscarCartoes();
        if (is_array($cartoes)) {
            return response(["mensagem" => $cartoes["mensagem"]], $cartoes["status"])
                ->header('Content-Type', 'application/problem');
        }

        $data = new DateTime();
        $dadosTime = getdate($data->getTimestamp());
        $ano = $dadosTime['year'];
        $mes = $dadosTime['mon'];
        $day = $dadosTime['mday'];
        foreach ($cartoes as $cartao) {
            $cartao_data = "$cartao->id$mes$ano";
            $fatura = $this->faturaRepository->buscarFaturaUmParametro('id_cartao_data',$cartao_data);

            if (is_array($fatura)) {
                return response(["mensagem" => $fatura["mensagem"]], $fatura["status"])
                    ->header('Content-Type', 'application/problem');
            }

            if (is_null($fatura)) {
                $nova_fatura = FaturaUtils::criarObjectFatura(
                    0.0,
                    new DateTime("$ano-$mes-$cartao->dia_vencimento"),
                    0,
                    0,
                    $cartao->id,
                    $cartao_data
                );
                $response = $this->faturaRepository->criarFatura($nova_fatura);
                if ($response["status"] == 500) {
                    return response(["mensagem" => $response["mensagem"]], 500)
                        ->header('Content-Type', 'application/problem');
                }
            }
        }
        return response(["mensagem" => "Faturas do mês checadas"], 200)
            ->header('Content-Type', 'application/json');
    }
}
