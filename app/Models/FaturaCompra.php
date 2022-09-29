<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class FaturaCompra extends Model
{
    public function __construct(?object $obj = null) {
        if ($obj) {
            $this->id_fatura = $obj->getIdFatura();
            $this->id_compra = $obj->getIdCompra();
            $this->descricao = $obj->getDescricao();
            $this->valor = $obj->getValor();
        }
    }

    public function fatura(){
        return $this->belongsTo(Fatura::class,'id_fatura');
    }

    public function compra(){
        return $this->belongsTo(Compra::class,'id_compra');
    }
    protected $table = 'fatura_compra';
    public $timestamps = false;
}

