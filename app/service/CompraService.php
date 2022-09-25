<?php

namespace App\service;

use App\DTOs\CompraDTO;
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

    public function __construct() {
        $this->compraRepository = new CompraRepository();
        $this->cartaoRepository = new CartaoRepository();
        $this->faturaRepository = new FaturaRepository();
    }

    public function buscarCompras(): Response {
        $response = $this->compraRepository->buscarCompras();
        if (is_array($response)) {
            return response(["mensagem" => $response["mensagem"]], $response["status"])
                ->header('Content-Type', 'application/problem');
        }
        return response($response, 200)
            ->header('Content-Type', 'application/json');
    }

    public function criarCompra(Request $request): Response {
        try {
            DB::beginTransaction();
            $mensagem = [];
            /*----criando compra e buscando o cartão---*/
            $compra = new CompraDTO($request); 
            $cartao = $this->cartaoRepository->buscarCartaoId($compra->getIdCartao());
            if (is_array($cartao)) {
                $mensagem = $cartao;
                return Response($mensagem["mensagem"], $mensagem["status"])
                    ->header('Content-Type', 'application/problem');
            }
            /*----alterando valor caso a parcela seja maior que 1---*/
            $compraParcelada = $compra->parcelas > 1;
            $compra->valor = $compraParcelada ? $compra->valor / $compra->parcelas : $compra->valor;
            echo "aqui";
            /*----tratando a data para cadastro da fatura---*/
            $data = new DateTime($compra->data_compra);
            $dadosTime = getdate($data->getTimestamp());
            $day = $dadosTime['mday'];

            if ($day > $cartao->dia_vencimento) {
                date_add($data, date_interval_create_from_date_string('1 Month'));
                $dadosTime = getdate($data->getTimestamp());
                $ano = $dadosTime['year'];
                $mes = $dadosTime['mon'];
            } else {
                $ano = $dadosTime['year'];
                $mes = $dadosTime['mon'];
            }

            $id_cartao_data = "$cartao->id$mes$ano";

            /*----buscando a fatura---*/
            $fatura = $this->faturaRepository->buscarFaturaUmParametro('id_cartao_data', $id_cartao_data);
            if (is_array($fatura)) {
                $mensagem = $fatura;
                return Response($mensagem["mensagem"], $mensagem["status"])
                    ->header('Content-Type', 'application/problem');
            }

            /*----criando a fatura---*/
            if (is_null($fatura)) {
                /*
            if ($day > $cartao->dia_vencimento) {
                $nova_data = date_add($data, date_interval_create_from_date_string('1 Month'));
                $dadosTime = getdate($nova_data->getTimestamp());
                $ano = $dadosTime['year'];
                $mes = $dadosTime['mon'];
                $day = $dadosTime['mday'];
            }
            */
                $id_cartao_data = "$cartao->id$mes$ano";
                $novaFatura = FaturaUtils::criarObjectFatura(
                    $compra->valor,
                    new DateTime("$ano-$mes-$cartao->dia_vencimento"),
                    0,
                    0,
                    $cartao->id,
                    $id_cartao_data
                );
                $response = $this->faturaRepository->criarFatura($novaFatura);
                if ($response["status"] == 500) {
                    return response(["mensagem" => $response["mensagem"]], $response["status"])
                        ->header('Content-Type', 'application/problem');
                }
            } else {
                $fatura->valor += $compra->valor;
                $response = $this->faturaRepository->criarFatura($fatura);
                if ($response["status"] == 500) {
                    return response(["mensagem" => $response["mensagem"]], $response["status"])
                        ->header('Content-Type', 'application/problem');
                }
            }

            if ($compraParcelada) {
                for ($i = 1; $i < $compra->parcelas; $i++) {
                    $nova_data = clone $data;
                    date_add($nova_data, date_interval_create_from_date_string($i . ' Month'));
                    $dadosTime = getdate($nova_data->getTimestamp());
                    $ano = $dadosTime['year'];
                    $mes = $dadosTime['mon'];
                    $day = $dadosTime['mday'];
                    $id_cartao_data = "$cartao->id$mes$ano";
                    $fatura = $this->faturaRepository->buscarFaturaUmParametro('id_cartao_data', $id_cartao_data);
                    if (is_null($fatura)) {
                        $novaFatura = FaturaUtils::criarObjectFatura(
                            $compra->valor,
                            new DateTime("$ano-$mes-$cartao->dia_vencimento"),
                            0,
                            0,
                            $cartao->id,
                            $id_cartao_data
                        );
                        $response = $this->faturaRepository->criarFatura($novaFatura);
                        if ($response["status"] == 500) {
                            return response(["mensagem" => $response["mensagem"]], $response["status"])
                                ->header('Content-Type', 'application/problem');
                        }
                    } else {
                        $fatura->valor += $compra->valor;
                        $response = $this->faturaRepository->criarFatura($fatura);
                        if ($response["status"] == 500) {
                            return response(["mensagem" => $response["mensagem"]], $response["status"])
                                ->header('Content-Type', 'application/problem');
                        }
                    }
                }
            }

            $response = $this->compraRepository->criarCompras($compra);
            if ($response["status"] == 500) {
                return response(["mensagem" => $response["mensagem"]], $response["status"])
                    ->header('Content-Type', 'application/problem');
            }
            DB::commit();

            return response(["mensagem" => "Compra cadastrada"], $response["status"])
                ->header('Content-Type', 'application/json');
        }catch (PDOException $th) {
            DB::rollBack();
            return response(["mensagem" => $th->getMessage()], 400)
                ->header('Content-Type', 'application/problem'); 
        }catch (BadRequestException $th) {
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
