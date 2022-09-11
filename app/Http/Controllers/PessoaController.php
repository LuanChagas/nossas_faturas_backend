<?php

namespace App\Http\Controllers;

use App\Models\Pessoa;
use App\repository\PessoaRepository;
use App\service\PessoaService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class PessoaController extends Controller {
    private $pessoaService;

    public function __construct() {
        $this->pessoaService = new PessoaService();
    }
    public function index():Response {
        return $this->pessoaService->buscarPessoas();
    }

    public function criar(Request $request):Response {
       $response = $this->pessoaService->criarPessoa($request);
       return $response;
    }
}
