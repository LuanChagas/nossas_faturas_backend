<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model {


    public function __construct(?object $pessoa=null) {
        if($pessoa){
            $this->nome = $pessoa->getNome();
        }
        
    }

    public function compras(){
        return $this->hasMany(Compra::class,'id_usuario');
    }
    
    protected $table = 'usuarios';
    public $timestamps = false;
}
