<?php

namespace App\Http\Controllers;

use App\service\FaturaService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FaturaController extends Controller {

    private $faturaService;

    function __construct() {
        $this->faturaService = new FaturaService();
    }


    public function index(): Response {
        return $this->faturaService->buscarFaturas();
    }

    public function criar(Request $request): Response {

        return $response =  $this->faturaService->criarFatura($request);
    }

    public function criarFaturaMes(): Response {
        return $this->faturaService->checarFaturasMes();
    }
}
