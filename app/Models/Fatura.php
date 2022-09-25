<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;


class Fatura extends Model {

    public function __construct(?object $faturaDTO = null) {
        if ($faturaDTO) {
            $this->valor = $faturaDTO->getValor();
            $this->data = $faturaDTO->getData();
            $this->isFechada = $faturaDTO->getIsFechada();
            $this->isPaga = $faturaDTO->getIsPaga();
            $this->id_cartao = $faturaDTO->getIdCartao();
            $this->id_cartao_data = $faturaDTO->getIdCartaoData();
        }
    }

    public function cartao() {
        $this->hasMany(Cartao::class, 'id_cartao');
    }
    protected $table = 'faturas';
    public $timestamps = false;
}
