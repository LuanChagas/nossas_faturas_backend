<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;


class Fatura extends Model {

    public function __construct(?object $obj = null) {
        if ($obj) {
            $this->id = $obj->getId();
            $this->valor = $obj->getValor();
            $this->data = $obj->getData();
            $this->isFechada = $obj->getIsFechada();
            $this->isPaga = $obj->getIsPaga();
            $this->id_cartao = $obj->getIdCartao();
            $this->id_cartao_data = $obj->getIdCartaoData();
        }
    }

    public function cartao() {
        $this->hasMany(Cartao::class, 'id_cartao');
    }
    protected $table = 'faturas';
    public $timestamps = false;
}
