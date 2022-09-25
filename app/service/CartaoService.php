<?php

namespace App\service;

use App\DTOs\CartaoDTO;
use App\Models\Cartao;
use App\repository\CartaoRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use PDOException;

class CartaoService {

    private $cartaoRepository;

    function __construct() {
        $this->cartaoRepository = new CartaoRepository();
    }

    public function buscarCartoes(): Response {
        try {
            $response =  $this->cartaoRepository->buscarCartoes();
            return Response($response)
                ->header('Content-Type', 'application/json');
        } catch (PDOException $th) {
            return Response(["mensagem" => $th->getMessage()], 500)
                ->header('Content-Type', 'application/problem');
        }
    }

    public function criarCartao(Request $request): Response {
        try {
            $cartaoDTO = new CartaoDTO($request);
            $cartao = new Cartao($cartaoDTO);
            $response = $this->cartaoRepository->criarCartao($cartao);
            return response(["mensagem" => $response["mensagem"]], 201)
                ->header('Content-Type', 'application/json');
        } catch (PDOException $th) {
            return Response(["mensagem" => $th->getMessage()], 500)
                ->header('Content-Type', 'application/problem');
        }
    }
}
