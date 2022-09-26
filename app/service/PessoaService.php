<?php

namespace App\service;

use App\DTOs\PessoaDTO;
use App\Models\Pessoa;
use App\repository\PessoaRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use PDOException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class PessoaService {
    private $pessoaRepository;

    public function __construct() {
        $this->pessoaRepository = new PessoaRepository();
    }

    public function buscarPessoas(): Response {
        try {
            $response = $this->pessoaRepository->buscarPessoas();
            return response($response)
                ->header('Content-Type', 'application/json');
        } catch (PDOException $th) {
            return response(["mensagem" => $th->getMessage()], 500)
                ->header('Content-Type', 'application/problem');
        }
    }

    public function criarPessoa(Request $request): Response {
        try {
            $pessoaDTO = new PessoaDTO($request);
            $pessoa = new Pessoa($pessoaDTO);
            $this->pessoaRepository->criarPessoa($pessoa);
            return response(["mensagem" => "Pessoa criada com sucesso"], 201)
                ->header('Content-Type', 'application/json');
        } catch (PDOException $th) {
            return response(["mensagem" => $th->getMessage()], 500)
                ->header('Content-Type', 'application/problem');
        } catch (BadRequestException $th) {
            return response(["mensagem" => $th->getMessage()], 400)
                ->header('Content-Type', 'application/problem');
        }
    }
}
