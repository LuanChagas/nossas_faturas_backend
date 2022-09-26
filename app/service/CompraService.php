<?php

namespace App\service;

use App\DTOs\CartaoDTO;
use App\DTOs\CompraDTO;
use App\DTOs\FaturaDTO;
use App\Models\Cartao;
use App\Models\Compra;
use App\Models\Fatura;

use App\repository\CartaoRepository;
use App\repository\CompraRepository;
use App\repository\FaturaRepository;
use App\Utils\FaturaUtils;

use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use PDOException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class CompraService {

    private $compraRepository;
    private $cartaoRepository;
    private $faturaRepository;
    private $faturaService;

    public function __construct() {
        $this->compraRepository = new CompraRepository();
        $this->cartaoRepository = new CartaoRepository();
        $this->faturaRepository = new FaturaRepository();
        $this->faturaService = new FaturaService();
    }

    public function buscarCompras(): Response {
        try {
            $response = $this->compraRepository->buscarCompras();
            return response($response, 200)
                ->header('Content-Type', 'application/json');
        } catch (PDOException $th) {
            return response(["mensagem" => $th->getMessage()], $response["status"])
                ->header('Content-Type', 'application/problem');
        }
    }

    public function criarCompra(Request $request): Response {
        try {
            DB::beginTransaction();
            /*----criando compra e buscando o cartão---*/
            $compra = new CompraDTO($request);
            $cartao = new CartaoDTO($this->cartaoRepository->buscarCartaoId($compra->getIdCartao()));

            /*----alterando valor caso a parcela seja maior que 1---*/
            $compraParcelada = $compra->getParcelas() > 1;
            $valorParcelado = $compraParcelada
                ? $compra->getValor() / $compra->getParcelas()
                : $compra->getValor();

            /*----tratando a data para cadastro da fatura---*/
            $data = new DateTime();
            $ano = date("Y", $data->getTimestamp());
            $mes = date("m", $data->getTimestamp());
            $dataCompra = new DateTime($compra->getDataCompra()->format("Y-m-d"));
            $dataFechamento = new DateTime("$ano-$mes-" . $cartao->getDiaFechamento());
            if ($dataCompra > $dataFechamento) {
                date_add($data, date_interval_create_from_date_string("1 Month"));
            }
            $this->faturaService->persistirFatura($cartao, $data, $valorParcelado);
            if ($compraParcelada) {
                for ($i = 1; $i < $compra->getParcelas(); $i++) {
                    $nova_data = clone $data;
                    date_add($nova_data, date_interval_create_from_date_string($i . ' Month'));
                    $this->faturaService->persistirFatura($cartao, $nova_data, $valorParcelado);
                }
            }

            $compra = new Compra($compra);
            $this->compraRepository->criarCompras($compra);

            DB::commit();

            return response(["mensagem" => "Compra Cadastrada",], 201)
                ->header('Content-Type', 'application/json');
        } catch (PDOException $th) {
            DB::rollBack();
            return response(["mensagem" => $th->getMessage()], 500)
                ->header('Content-Type', 'application/problem');
        } catch (BadRequestException $th) {
            DB::rollBack();
            return response(["mensagem" => "Erro na operaçao: " . $th->getMessage()], 400)
                ->header('Content-Type', 'application/problem');
        } catch (\Throwable $th) {
            DB::rollBack();
            return response(["mensagem" => "Erro na operaçao: " . $th->getMessage()], 500)
                ->header('Content-Type', 'application/problem');
        }
    }
}
