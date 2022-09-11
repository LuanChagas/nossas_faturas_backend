<?php

namespace App\repository;

use App\Models\Compra;
use Illuminate\Database\Eloquent\Collection;
use PDOException;

class CompraRepository {

    public function buscarCompras(): Collection|array {
        try {
            return Compra::all('*');
        } catch (PDOException $th) {
            return [
                "mensagem" => "erro ao buscar compras. Código " . (int)$th->getCode(),
                "status" => 500,
            ];
        }
    }

    public function criarCompras(Compra $compra):array{
        try {
            $compra->save();
            return [
                "mensagem" => "Compra Cadastrada",
                "status" => 201,
            ];
        } catch (PDOException $th) {
            return [
                "mensagem" => "erro ao ao criar compras. Código " . $th->getMessage(),
                "status" => 500,
            ];
        }
    }
}
