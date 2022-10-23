<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class FaturaCompra extends Model
{
    public function __construct(?object $obj = null) {
        if ($obj) {
            $this->id_fatura = $obj->getIdFatura();
            $this->id_compra = $obj->getIdCompra();
            $this->parcela = $obj->getParcela();
            $this->valor = $obj->getValor();
        }
    }

    protected $casts = [
        'valor' => 'double',
    ];

    public function fatura(){
        return $this->belongsTo(Fatura::class,'id_fatura');
    }

    public function compra(){
        return $this->belongsTo(Compra::class,'id_compra');
    }
    protected $table = 'fatura_compra';
    public $timestamps = false;
}

