<?php

namespace App\Http\Controllers;

use App\Models\Cartao;
use App\service\CartaoService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CartaoController extends Controller {

    private $cartaoService;

    public function __construct() {
        $this->cartaoService = new CartaoService();
    }

    public function index():Response {
        return $this->cartaoService->buscarCartoes();
    }

    public function criar(Request $request):Response {
        return $this->cartaoService->criarCartao($request);
    }
}
