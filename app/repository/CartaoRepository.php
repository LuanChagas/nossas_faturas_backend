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
                "mensagem" => "erro ao buscar cartões: " . (int)$th->getCode(),
                "status" => "500",
            ];
        }
    }

    public function criarCartao(Cartao $compra): array {
        try {
            $compra->save();
            return [
                "mensagem" => "Cartao Criado",
                "status" => "201",
            ];
        } catch (PDOException $th) {
            return [
                "mensagem" => "erro ao criar cartão: " . (int)$th->getCode(),
                "status" => "500",
            ];
        }
    }

    public function buscarCartaoId(Number $id): Cartao|array {
        try {
            return Cartao::find($id);
        } catch (PDOException $th) {
            return [
                "mensagem" => "erro ao encontrar cartão: " . (int)$th->getCode(),
                "status" => "500",
            ];
        }
    }
}
