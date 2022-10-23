<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Compra extends Model {

    public function __construct(?object $obj = null) {
        if ($obj) {
            $this->descricao = $obj->getDescricao();
            $this->local = $obj->getLocal();
            $this->data_compra = $obj->getDataCompra();
            $this->valor = $obj->getValor();
            $this->parcelas = $obj->getParcelas();
            $this->id_usuario = $obj->getIdUsuario();
            $this->id_cartao = $obj->getIdCartao();
        }
    }
    protected $casts = [
        'valor' => 'double',
    ];
    public function pessoas(){
        return $this->hasMany(Pessoa::class,'id_usuario');
    }

    public function cartao(){
        return $this->hasMany(Cartao::class,'id_cartao');
    }

    protected $table = 'compras';
    public $timestamps = false;
}
