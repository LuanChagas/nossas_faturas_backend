<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Cartao extends Model
{
    public function __construct(?object $atributos=null) {
        if($atributos){
            $this->id = $atributos->id;
            $this->nome = $atributos->nome;
            $this->pix = $atributos->pix;
            $this->dia_fechamento = $atributos->dia_fechamento;
            $this->dia_vencimento = $atributos->dia_vencimento;
            $this->limite_total = $atributos->limite_total;
            $this->limite_parcial = $atributos->limite_parcial;
        }
       
    }

    public function fatura(){
        $this->hasMany(Fatura::class);
    } 
    protected $table = 'cartoes';
    public $timestamps = false;
}
