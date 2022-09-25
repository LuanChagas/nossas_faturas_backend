<?php

namespace App\service;

use App\DTOs\CartaoDTO;
use App\DTOs\FaturaDTO;
use App\Models\Cartao;
use App\Models\Fatura;
use App\repository\CartaoRepository;
use App\repository\FaturaRepository;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use PDOException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class FaturaService {

    private $faturaRepository;
    private $cartaoRepository;

    function __construct() {
        $this->faturaRepository = new FaturaRepository();
        $this->cartaoRepository = new CartaoRepository();
    }

    public function buscarFaturas(): Response {
        try {
            $response = $this->faturaRepository->buscarTodasFaturas();
            return response($response, 200)->header('Content-Type', 'application/json');
        } catch (PDOException $th) {
            return response(["mensagem" => $th->getMessage()], 500)
                ->header('Content-Type', 'application/problem');
        }
    }

    public function criarFatura(Request $request): Response {
        try {
            $faturaDTO = new FaturaDTO($request);
            $fatura = new Fatura($faturaDTO);
            $response = $this->faturaRepository->criarFatura($fatura);
            return response(["mensagem" => $response["mensagem"]], 201)
                ->header('Content-Type', 'application/json');
        } catch (BadRequestException $th) {
            return response(["mensagem" => $th->getMessage()], 400)
                ->header('Content-Type', 'application/problem');
        } catch (PDOException $th) {
            return response(["mensagem" => $th->getMessage()], 500)
                ->header('Content-Type', 'application/problem');
        }
    }

    public function criarFaturaMes(): Response {
        try {
            $cartoes = $this->cartaoRepository->buscarCartoes();
            $listaCartao = $cartoes->map(function ($x) {
                $cartaoDTO = new CartaoDTO();
                $cartaoDTO->arrayToCartaoDTO($x);
                return $cartaoDTO;
            })->toArray();
            $data = new DateTime();
            $ano = date('Y', $data->getTimestamp());
            $mes = date('m', $data->getTimestamp());
            foreach ($listaCartao as $cartao) {
                $cartao_data = $cartao->getId() . "$mes$ano";
                $fatura = $this->faturaRepository->buscarFaturaUmParametro('id_cartao_data', $cartao_data);
                $faturaDTO = new FaturaDTO($fatura);
                if (!isset($faturaDTO)) {
                    $nova_fatura = new FaturaDTO();
                    $nova_fatura->setValor(0.0);
                    $nova_fatura->setData("$ano-$mes-" . $cartao->getDiaVencimento());
                    $nova_fatura->setIsFechada(false);
                    $nova_fatura->setIsPaga(false);
                    $nova_fatura->setIdCartao($cartao->getId());
                    $nova_fatura->setIdCartaoData($cartao_data);
                    $nova_fatura_model = new Fatura($nova_fatura);
                    $this->faturaRepository->criarFatura($nova_fatura_model);
                }
            }
            return response(["mensagem" => "Faturas do mês checadas"], 200)
                ->header('Content-Type', 'application/json');
        } catch (BadRequestException $th) {
            return response(["mensagem" => $th->getMessage()], 400)
                ->header('Content-Type', 'application/problem');
        } catch (\PDOException $th) {
            return response(["mensagem" => $th->getMessage()], 500)
                ->header('Content-Type', 'application/problem');
        } catch (\Throwable $th) {
            return response(["mensagem" => $th->getMessage()], 500)
                ->header('Content-Type', 'application/problem');
        }
    }
}
