<?php

namespace App\repository;

use App\Models\Cartao;
use Faker\Core\Number;
use Illuminate\Database\Eloquent\Collection;
use PDOException;

class CartaoRepository {

    public function buscarCartoes(): Collection {
        try {
            return Cartao::all('*');
        } catch (PDOException $th) {
            return  throw new PDOException(
                "Erro ao econtrar Cartões. Código: " . $th->getCode()
            );
        }
    }

    public function criarCartao(Cartao $compra) {
        try {
            $compra->save();
        } catch (PDOException $th) {
            return throw new PDOException(
                "Erro ao criar Compra. Código: " . $th->getCode()
            );
        }
    }

    public function buscarCartaoId(Int $id): Cartao {
        try {
            return Cartao::find($id);
        } catch (PDOException $th) {
            return  throw new PDOException(
                "Erro ao econtrar Cartão. Código: " . $th->getCode()
            );
        }
    }
}
