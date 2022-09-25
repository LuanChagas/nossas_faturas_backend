<?php


namespace App\repository;

use App\Models\Pessoa;
use Illuminate\Database\Eloquent\Collection;
use PDOException;

class PessoaRepository {


    public function buscarPessoas(): Collection|PDOException {
        try {
            return Pessoa::with('compras')->get(["sds"]);
        } catch (PDOException $th) {
            return throw new PDOException("Erro ao buscar PessoaS. Código: " . $th->getCode(),
            (int)$th->getCode());
        }
    }
    public function criarPessoa(Pessoa $pessoa): array|PDOException {
        try {
            $pessoa->save();
            return [
                "mensagem" => "Pessoa criada com sucesso",
                "status" => 201
            ];
        } catch (PDOException $th) {
            return throw new PDOException("Erro ao criar Pessoa. Código: " . $th->getCode(),
            (int)$th->getCode());
        }
    }
}
