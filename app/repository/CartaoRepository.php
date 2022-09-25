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
            return  throw new PDOException(
                "Erro ao econtrar Cartões. Código: " . $th->getCode(),
                (int)$th->getCode()
            );
        }
    }

    public function criarCartao(Cartao $compra): array|PDOException {
        try {
            $compra->save();
            return [
                "mensagem" => "Cartao Criado",
                "status" => 201
            ];
        } catch (PDOException $th) {
            return  throw new PDOException("Erro ao criar cartão. Código: " . $th->getCode(),
            (int)$th->getCode());
        }
    }

    public function buscarCartaoId(Int $id): Cartao|array|null {
        try {
            return Cartao::find($id);
        } catch (PDOException $th) {
            return  throw new PDOException("Erro ao econtrar Cartão. Código: " . $th->getCode(),
            (int)$th->getCode());
        }
    }
}
