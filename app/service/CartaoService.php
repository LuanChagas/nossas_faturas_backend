<?php

namespace App\service;

use App\Models\Cartao;
use App\repository\CartaoRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CartaoService{

    private $cartaoRepository;

    function __construct()
    {
        $this->cartaoRepository = new CartaoRepository();
    }

    public function buscarCartoes(): Response {
        $response =  $this->cartaoRepository->buscarCartoes();
        
        if(is_array($response)){
            return Response($response["mensagem"],$response["status"]) 
             ->header('Content-Type', 'text/plain');
        }
        return Response($response) 
        ->header('Content-Type', 'application/json');
    }


    public function criarCartao(Request $request):Response{
        $cartao = new Cartao($request);
        $response = Response($this->cartaoRepository->criarCartao($cartao));
        return $response;
    }
}