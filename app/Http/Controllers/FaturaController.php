<?php

namespace App\Http\Controllers;


use App\Models\Fatura;
use App\service\FaturaService;
use Illuminate\Http\Request;

class FaturaController extends Controller {

    private $faturaService;

    function __construct(){
        $this->faturaService = new FaturaService();
    }


    public function index() {
        return $this->faturaService->buscarFaturas();
    }

    public function criar(Request $request) {

        $resposta =  $this->faturaService->criarFatura($request);
        return response($resposta["mensagem"], $resposta["status"])
        ->header('Content-Type', 'application/json');
    }

    public function criarFaturaMes() {
        return $this->faturaService->criarFaturaMes();
    }
    
}
