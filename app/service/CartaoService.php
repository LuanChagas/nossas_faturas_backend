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
            $cartoes = $this->cartaoRepository->buscarCartoes();
            $response = $cartoes->map(function ($x) {
                $cartaoDTO = new CartaoDTO($x);
                return $cartaoDTO->objecToArray();
            })->toArray();
            return Response($response, 200)
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
            $this->cartaoRepository->criarCartao($cartao);
            return response(["mensagem" => "Cartao Criado"], 201)
                ->header('Content-Type', 'application/json');
        } catch (PDOException $th) {
            return Response(["mensagem" => $th->getMessage()], 500)
                ->header('Content-Type', 'application/problem');
        }
    }
}
