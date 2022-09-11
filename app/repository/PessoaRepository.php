<?php


namespace App\repository;

use App\Models\Pessoa;
use Illuminate\Database\Eloquent\Collection;
use PDOException;

class PessoaRepository {


    public function buscarPessoas(): Collection|array {
        try {
            return Pessoa::all('*');
        } catch (PDOException $th) {
            return [
                "mensagem" => "Erro ao buscar Pessoas. Código " . $th->getCode(),
                "status" => 500
            ];
        }
    }
    public function criarPessoa(Pessoa $pessoa): array {
        try {
            $pessoa->save();
            return [
                "mensagem" => "Pessoa criada com sucesso",
                "status" => 201
            ];
        } catch (PDOException $th) {
            return [
                "mensagem" => "Erro ao criar Pessoa. Código " . $th->getCode(),
                "status" => 500
            ];
        }
    }
}
