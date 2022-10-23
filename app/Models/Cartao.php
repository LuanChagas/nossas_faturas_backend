<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Cartao extends Model
{
    public function __construct(?object $atributos=null) {
        if($atributos){
            if($atributos->getId()){
            $this->id = $atributos->getId();
            }
            $this->nome = $atributos->getNome();
            $this->pix = $atributos->getPix();
            $this->dia_fechamento = $atributos->getDiaFechamento();
            $this->dia_vencimento = $atributos->getDiaVencimento();
            $this->limite_total = $atributos->getLimiteTotal();
            $this->limite_parcial = $atributos->getLimiteParcial();
        }
       
    }
    protected $casts = [
        'limite_total' => 'double',
        'limite_parcial' => 'double',
    ];

    public function fatura(){
        $this->hasMany(Fatura::class);
    } 
    protected $table = 'cartoes';
    public $timestamps = false;
}
