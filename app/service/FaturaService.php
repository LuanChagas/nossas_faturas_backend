<?php

namespace App\service;

use App\DTOs\CartaoDTO;
use App\DTOs\FaturaDTO;
use App\Models\Fatura;
use App\repository\CartaoRepository;
use App\repository\FaturaRepository;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use PDOException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

use function PHPUnit\Framework\isNull;

class FaturaService {

    private $faturaRepository;
    private $cartaoRepository;

    function __construct() {
        $this->faturaRepository = new FaturaRepository();
        $this->cartaoRepository = new CartaoRepository();
    }

    public function buscarFaturas(): Response {
        try {
            $fatura = $this->faturaRepository->buscarTodasFaturas();
            $response = $fatura->map(function ($x) {
                $fat = new FaturaDTO($x);
                return $fat->objectToArray();
            });
            return response($response, 200)->header('Content-Type', 'application/json');
        } catch (PDOException $th) {
            return response(["mensagem" => $th->getMessage()], 500)
                ->header('Content-Type', 'application/problem');
        }
    }

    public function criarFatura(Object $request): Response {
        try {
            $faturaDTO = new FaturaDTO($request);
            $fatura = new Fatura($faturaDTO);
            $this->faturaRepository->criarFatura($fatura);
            return response(["mensagem" => "Fatura Criada"], 201)
                ->header('Content-Type', 'application/json');
        } catch (BadRequestException $th) {
            return response(["mensagem" => $th->getMessage()], 400)
                ->header('Content-Type', 'application/problem');
        } catch (PDOException $th) {
            return response(["mensagem" => $th->getMessage()], 500)
                ->header('Content-Type', 'application/problem');
        }
    }

    public function persistirFatura($cartao, $data, $valor = null):int {
        if (is_null($valor)) {
            $valor = 0.0;
        }
        $ano = date('Y', $data->getTimestamp());
        $mes = date('m', $data->getTimestamp());
        $id_cartao_data = $cartao->getId() . "$mes$ano";

        $faturaModel = $this->faturaRepository->buscarFaturaUmParametro('id_cartao_data', $id_cartao_data);
        if (is_null($faturaModel)) {
            $fatura = new Fatura();
            $fatura->valor = $valor;
            $fatura->data =  new DateTime("$ano-$mes-" . $cartao->getDiaVencimento());
            $fatura->isFechada = 0;
            $fatura->isPaga = 0;
            $fatura->id_cartao = $cartao->getId();
            $fatura->id_cartao_data = $id_cartao_data;
            $id_fatura_inserted = $this->faturaRepository->criarFatura($fatura);
            return $id_fatura_inserted;
        } else {
            $faturaModel->valor += $valor;
            $this->faturaRepository->criarFatura($faturaModel);
            return $faturaModel->id;
        }
    }

    public function checarFaturasMes(): Response {
        try {
            $cartoes = $this->cartaoRepository->buscarCartoes();
            $listaCartao = $cartoes->map(function ($x) {
                $cartaoDTO = new CartaoDTO($x);
                return $cartaoDTO;
            })->toArray();
            $data = new DateTime();
            foreach ($listaCartao as $cartao) {
                $this->persistirFatura($cartao, $data);
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
