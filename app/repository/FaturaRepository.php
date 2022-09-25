<?php

namespace App\repository;


use App\Models\Fatura;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;
use PDOException;

class FaturaRepository {

    function __construct() {
    }

    public function buscarTodasFaturas(): Collection|PDOException {

        try {
            return Fatura::all('*');
        } catch (PDOException $th) {
            return throw new PDOException("Erro ao encontrar Faturas. Código: " . $th->getCode(),
            (int)$th->getCode());
        }
    }

    public function criarFatura(Fatura $fatura): array|PDOException {
        try {
            $fatura->save();
            return [
                "mensagem" => "Fatura Criada",
            ];
        } catch (PDOException $th) {
            $mensagem="";
            if($th->getCode() == 23000){
                $mensagem = "Já existe uma fatura registrada correspondente ao cartão registrada.";
            }
            return throw new PDOException("Erro ao criar Fatura. $mensagem Código: " . $th->getCode(),
            (int)$th->getCode());
        }
    }

    public function buscarFaturaUmParametro(String $campo,String $valor): Fatura|PDOException|null {
        try {
            return Fatura::where($campo, $valor)->first();
        } catch (PDOException $th) {
            return throw new PDOException("Erro ao encontra fatura. Código: " . $th->getCode(),
            (int)$th->getCode());
        }
    }
}
