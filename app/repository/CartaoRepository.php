<?php

namespace App\repository;

use App\Models\Cartao;
use Faker\Core\Number;
use Illuminate\Database\Eloquent\Collection;
use PDOException;

class CartaoRepository {

    public function buscarCartoes(): Collection|array {
        try {
            return Cartao::all('*');
        } catch (PDOException $th) {
            return [
                "mensagem" => "erro ao buscar cartões. Código " . (int)$th->getCode(),
                "status" => 500
            ];
        }
    }

    public function criarCartao(Cartao $compra): array {
        try {
            $compra->save();
            return [
                "mensagem" => "Cartao Criado",
                "status" => 201
            ];
        } catch (PDOException $th) {
            return [
                "mensagem" => "erro ao criar cartão. Código " . (int)$th->getCode(),
                "status" => 500
            ];
        }
    }

    public function buscarCartaoId(Int $id): Cartao|array|null {
        try {
            return Cartao::find($id);
        } catch (PDOException $th) {
            return [
                "mensagem" => "erro ao encontrar cartão. Código " . (int)$th->getCode(),
                "status" => 500
            ];
        }
    }
}
