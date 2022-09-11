<?php

namespace App\service;

use App\Models\Pessoa;
use App\repository\PessoaRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PessoaService {
    private $pessoaRepository;

    public function __construct() {
        $this->pessoaRepository = new PessoaRepository();
    }

    public function buscarPessoas(): Response {
        $response = $this->pessoaRepository->buscarPessoas();
        if (is_array($response)) {
            return response(["mensagem" => $response["mensagem"]], $response["status"])
                ->header('Content-Type', 'application/problem');
        }
        return response($response)
            ->header('Content-Type', 'application/json');
    }

    public function criarPessoa(Request $request): Response {
        $pessoa = new Pessoa($request);
        $response = $this->pessoaRepository->criarPessoa($pessoa);
        $header = $response["status"] != 500 ? 'application/json' : 'application/problem';
        return response(["mensagem" => $response["mensagem"]], $response["status"])
            ->header('Content-Type', $header);
    }
}
