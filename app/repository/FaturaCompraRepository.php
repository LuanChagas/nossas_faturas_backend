<?php

namespace App\repository;

use App\Models\FaturaCompra;
use Illuminate\Database\Eloquent\Collection;
use PDOException;

class FaturaCompraRepository {

    public function buscarFaturaCompra(): Collection|array {
        try {
            return FaturaCompra::with('fatura')->with('compra')->get(["*"]);
        } catch (PDOException $th) {
            return throw new PDOException("Erro ao encontrar compras. Código " .
                $th->getCode());
        }
    }

    public function criarCompras(FaturaCompra $compra) {
        try {
            $compra->save();
        } catch (PDOException $th) {
            return throw new PDOException("Erro ao criar FaturaCompra. Código: " . $th->getCode());
        }
    }
}
