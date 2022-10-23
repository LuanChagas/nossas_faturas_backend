<?php

namespace App\service;

use App\DTOs\CartaoDTO;
use App\DTOs\CompraDTO;
use App\DTOs\FaturaCompraDTO;
use App\DTOs\FaturaDTO;
use App\Models\Cartao;
use App\Models\Compra;
use App\Models\Fatura;
use App\Models\FaturaCompra;
use App\repository\CartaoRepository;
use App\repository\CompraRepository;
use App\repository\FaturaCompraRepository;
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
    private $faturaCompraRepository;

    public function __construct() {
        $this->compraRepository = new CompraRepository();
        $this->cartaoRepository = new CartaoRepository();
        $this->faturaRepository = new FaturaRepository();
        $this->faturaService = new FaturaService();
        $this->faturaCompraRepository = new FaturaCompraRepository();
    }

    public function buscarCompras(): Response {
        try {
            $response = $this->compraRepository->buscarCompras();
            return response($response, 200)
                ->header('Content-Type', 'application/json');
        } catch (PDOException $th) {
            return response(["mensagem" => $th->getMessage()], 500)
                ->header('Content-Type', 'application/problem');
        }
    }

    public function criarCompra(Request $request): Response {
        try {
            DB::beginTransaction();
            /*----buscando o cartão e atualizando o limite parcial---*/
   
            $compraDTO = new CompraDTO($request);
            $cartao =  $this->cartaoRepository->buscarCartaoId($compraDTO->getIdCartao());
            $cartaoDTO = new CartaoDTO($cartao);
            $cartaoDTO->setLimiteParcial(
                $cartaoDTO->getLimiteParcial() - $compraDTO->getValor()
            );
            $cartao->limite_parcial = $cartaoDTO->getLimiteParcial();
            $this->cartaoRepository->criarCartao($cartao);
            
            /*----alterando valor caso a parcela seja maior que 1---*/
            $compraParcelada = $compraDTO->getParcelas() > 1;
            $valorParcelado = $compraParcelada
                ? $compraDTO->getValor() / $compraDTO->getParcelas()
                : $compraDTO->getValor();
                
            /*----tratando a data para cadastro da fatura---*/
            $data = new DateTime();
            $ano = date("Y", $data->getTimestamp());
            $mes = date("m", $data->getTimestamp());
            $dataCompra = new DateTime($compraDTO->getDataCompra()->format("Y-m-d"));
            $dataFechamento = new DateTime("$ano-$mes-" . $cartaoDTO->getDiaFechamento());
            if ($dataCompra > $dataFechamento) {
                date_add($data, date_interval_create_from_date_string("1 Month"));
            }
            $compra = new Compra($compraDTO);
            $id_compra_inserted = $this->compraRepository->criarCompras($compra);
            $id_fatura_inserted = $this->faturaService->persistirFatura($cartaoDTO, $data, $valorParcelado);
            $faturaCompraDTO = new FaturaCompraDTO();
            $faturaCompraDTO->setIdCompra($id_compra_inserted);
            $faturaCompraDTO->setIdFatura($id_fatura_inserted);
            $faturaCompraDTO->setParcela('1'.'/'.$compraDTO->getParcelas());
            $faturaCompraDTO->setValor($valorParcelado);
            $faturaCompra = new FaturaCompra($faturaCompraDTO);
            $this->faturaCompraRepository->criarCompras($faturaCompra);

            if ($compraParcelada) {
                for ($i = 1; $i < $compraDTO->getParcelas(); $i++) {
                    $nova_data = clone $data;
                    date_add($nova_data, date_interval_create_from_date_string($i . ' Month'));
                    $id_compra_inserted = $this->faturaService->persistirFatura($cartaoDTO, $nova_data, $valorParcelado);
                    $faturaCompraDTO->setIdFatura($id_fatura_inserted);
                    $faturaCompraDTO->setParcela($i+1 .'/'.$compraDTO->getParcelas());
                    $faturaCompraDTO->setValor($valorParcelado);
                    $faturaCompra = new FaturaCompra($faturaCompraDTO);
                    $this->faturaCompraRepository->criarCompras($faturaCompra);
                }
            }

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

    public function buscarFaturaCompra() {
        try {
            $response = $this->faturaCompraRepository->buscarFaturaCompra();
               return  response()->json($response
               , 200, [], JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
        } catch (PDOException $th) {
            return response(["mensagem" => $th->getMessage()], 500)
                ->header('Content-Type', 'application/problem');
        }
    }
}
