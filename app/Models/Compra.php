<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Compra extends Model {

    public function __construct(?object $obj = null) {
        if ($obj) {
            $this->nome = $obj->getNome();
            $this->descricao = $obj->getDescricao();
            $this->data_compra = $obj->getDataCompra();
            $this->valor = $obj->getValor();
            $this->parcelas = $obj->getParcelas();
            $this->id_pessoa = $obj->getIdPessoa();
            $this->id_cartao = $obj->getIdCartao();
        }
    }

    public function pessoas(){
        return $this->hasMany(Pessoa::class,'id_pessoa');
    }

    public function cartao(){
        return $this->hasMany(Cartao::class,'id_cartao');
    }

    protected $table = 'compras';
    public $timestamps = false;
}
