<?php

namespace App\Http\Controllers;

use App\Models\Cartao;
use App\Models\Compra;
use App\Models\Fatura;
use DateTime;
use Illuminate\Http\Request;
use App\Http\service\FaturaService;
use App\service\CompraService;
use Illuminate\Http\Response;

class CompraController extends Controller {

    private $compraService;
   

    public function __construct()
    {
        $this->compraService = new CompraService();
    }

    public function index():Response {
        return $this->compraService->buscarCompras();
    }

    public function criar(Request $request):Response {
        return $this->compraService->criarCompra($request);      
    }

    public function compraFatura() {
        return $this->compraService->buscarFaturaCompra();      
    }
}
