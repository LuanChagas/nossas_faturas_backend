<?php

namespace App\service;

use App\Models\Cartao;
use App\repository\CartaoRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CartaoService {

    private $cartaoRepository;

    function __construct() {
        $this->cartaoRepository = new CartaoRepository();
    }

    public function buscarCartoes(): Response {
        $response =  $this->cartaoRepository->buscarCartoes();

        if (is_array($response)) {
            return Response(["mensagem" => $response["mensagem"]], $response["status"])
                ->header('Content-Type', 'application/problem');
        }
        return Response($response)
            ->header('Content-Type', 'application/json');
    }


    public function criarCartao(Request $request): Response {
        $cartao = new Cartao($request);
        $response = $this->cartaoRepository->criarCartao($cartao);
        $header = $response["status"] != 500 ? 'application/json' : 'application/problem';
        return response(["mensagem" => $response["mensagem"]], $response["status"])
            ->header('Content-Type', $header);
    }
}
