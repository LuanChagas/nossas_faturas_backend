<?php

namespace App\repository;


use App\Models\Fatura;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;
use PDOException;

class FaturaRepository {

    function __construct() {
    }

    public function buscarTodasFaturas(): Collection|array {

        try {
            return Fatura::all('*');
        } catch (PDOException $th) {
            return [
                "mensagem" => "erro ao buscar faturas. Código  " . (int)$th->getCode(),
                "status" => 500,
            ];
        }
    }

    public function criarFatura(Fatura $fatura): array {
        try {
            $fatura->save();
            return [
                "mensagem" => "Fatura Criada",
                "status" => 201,
            ];
        } catch (PDOException $th) {
            return [
                "mensagem" => "erro ao criar fatura. Código " . (int)$th->getCode(),
                "status" => 500,
            ];
        }
    }

    public function buscarFaturaUmParametro(String $campo,String $valor): Fatura|array|null {
        try {
            return Fatura::where($campo, $valor)->first();
        } catch (PDOException $th) {
            return [
                "mensagem" => "erro ao buscar fatura. Código " . (int)$th->getCode(),
                "status" => 500,
            ];
        }
    }
}
