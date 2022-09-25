<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Compra extends Model {

    public function __construct(?Request $atributos = null) {
        if ($atributos) {
            $this->nome = $atributos->nome;
            $this->descricao = $atributos->descricao;
            $this->data_compra = $atributos->data_compra;
            $this->valor = $atributos->valor;
            $this->parcelas = $atributos->parcelas;
            $this->id_pessoa = $atributos->id_pessoa;
            $this->id_cartao = $atributos->id_cartao;
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
