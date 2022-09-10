<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;


class Fatura extends Model {

    public function __construct(?object $atributos = null) {
        if ($atributos) {
            $this->valor = $atributos->valor;
            $this->data = $atributos->data;
            $this->isFechada = $atributos->isFechada;
            $this->isPaga = $atributos->isPaga;
            $this->id_cartao = $atributos->id_cartao;
            $this->id_cartao_data = $atributos->id_cartao_data;
        }
    }

    public function cartao() {
        $this->belongsTo(Cartao::class);
    }
    protected $table = 'faturas';
    public $timestamps = false;
}
