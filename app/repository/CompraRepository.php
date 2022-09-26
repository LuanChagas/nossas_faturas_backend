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
            return throw new PDOException("Erro ao encontrar compras. Código ".
             $th->getCode());
        }
    }

    public function criarCompras(Compra $compra){
        try {
            $compra->save();
        } catch (PDOException $th) {
            return throw new PDOException("Erro ao criar Compra. Código: " . $th->getCode());
        }
    }
}
