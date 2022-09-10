<?php

namespace App\repository;

use App\Models\Compra;
use Illuminate\Database\Eloquent\Collection;
use PDOException;

class CompraRepository {

    public function buscarCompras(): Collection {
        try {
            return Compra::all('*');
        } catch (PDOException $th) {
            return [
                "mensagem" => "erro ao buscar compras: " . (int)$th->getCode(),
                "status" => "500",
            ];
        }
    }

    public function criarCompras(Compra $compra):array{
        try {
            $compra->save();
        } catch (PDOException $th) {
            return [
                "mensagem" => "erro ao ao criar compras: " . (int)$th->getCode(),
                "status" => "500",
            ];
        }
    }
}
