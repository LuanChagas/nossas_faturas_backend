<?php

namespace App\repository;


use App\Models\Fatura;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;
use PDOException;

class FaturaRepository {

    function __construct() {
    }

    public function buscarTodasFaturas(): Collection{

        try {
            return Fatura::all('*');
        } catch (PDOException $th) {
            return throw new PDOException("Erro ao encontrar Faturas. Código: " . $th->getCode());
        }
    }

    public function criarFatura(Fatura $fatura) {
        try {
            $fatura->save();
        } catch (PDOException $th) {
            return throw new PDOException("Erro ao criar Fatura. Código: " . $th->getCode());
        }
    }


    public function buscarFaturaUmParametro(String $campo, String $valor): Fatura|null {
        try {
            return Fatura::where($campo, $valor)->first();
        } catch (PDOException $th) {
            return throw new PDOException("Erro ao encontra fatura. Código: " . $th->getCode());
        }
    }
}
