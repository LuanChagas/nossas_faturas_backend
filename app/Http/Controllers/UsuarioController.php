<?php

namespace App\Http\Controllers;

use App\service\UsuarioService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class UsuarioController extends Controller {
    private $pessoaService;

    public function __construct() {
        $this->pessoaService = new UsuarioService();
    }
    public function index():Response {
        return $this->pessoaService->buscarUsuarios();
    }

    public function criar(Request $request):Response {
       $response = $this->pessoaService->criarUsuario($request);
       return $response;
    }
}
