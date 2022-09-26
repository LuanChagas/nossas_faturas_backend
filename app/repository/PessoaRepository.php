<?php


namespace App\repository;

use App\Models\Pessoa;
use Illuminate\Database\Eloquent\Collection;
use PDOException;

class PessoaRepository {


    public function buscarPessoas(): Collection {
        try {
            return Pessoa::with('compras')->get(["*"]);
        } catch (PDOException $th) {
            return throw new PDOException(
                "Erro ao buscar Pessoas. Código: " . $th->getCode()
            );
        }
    }
    public function criarPessoa(Pessoa $pessoa) {
        try {
            $pessoa->save();
        } catch (PDOException $th) {
            return throw new PDOException(
                "Erro ao criar Pessoa. Código: " . $th->getCode()
            );
        }
    }
}
