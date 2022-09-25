<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class FaturaCompra extends Model
{
    public function __construct(?object $atributos = null) {
        if ($atributos) {
            $this->id_fatura = $atributos->id_fatura;
            $this->id_compra = $atributos->id_compra;
            $this->descricao = $atributos->descricao;
            $this->valor = $atributos->valor;
        }
    }

    public function fatura(){
        $this->belongsTo(Fatura::class,'id_fatura');
    }

    public function compra(){
        $this->belongsTo(Compra::class,'id_compra');
    }
    protected $table = 'fatura_compra';
    public $timestamps = false;
}

