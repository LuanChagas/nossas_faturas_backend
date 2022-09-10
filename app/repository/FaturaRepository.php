<?php

namespace App\repository;


use App\Models\Fatura;
use Illuminate\Database\Eloquent\Collection;
use PDOException;

class FaturaRepository {

    function __construct() {
    }

    public function buscarTodasFaturas(): Collection {

        try {
            return Fatura::all('*');
        } catch (PDOException $th) {
            return ["mensagem"=>"erro ao buscar faturas: ". (int)$th->getCode( ) ,
            "status"=>"500",
        ];
        
    }
}

    public function criarFatura(Fatura $fatura):Array {
        try {
             $fatura->save();
             return ["mensagem"=>"Fatura Criada",
                    "status"=>"201",
                ];
        } catch (PDOException $th) {
            return ["mensagem"=>"erro ao criar fatura: ". (int)$th->getCode( ) ,
            "status"=>"500",
        ];
        }
    }

    public function criarFaturaMes():String {
        return "Faturas atualizada";
    }
}
