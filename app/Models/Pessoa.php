<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model {


    public function __construct(?object $pessoa=null) {
        if($pessoa){
            $this->nome = $pessoa->getNome();
        }
        
    }

    public function compras(){
        return $this->hasMany(Compra::class,'id_pessoa');
    }
    
    protected $table = 'pessoas';
    public $timestamps = false;
}
