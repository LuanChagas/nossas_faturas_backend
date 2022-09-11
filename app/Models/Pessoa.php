<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model {


    public function __construct(?object $attr=null) {
        if($attr){
            $this->nome = $attr->nome;
        }
        
    }
    protected $table = 'pessoas';
    public $timestamps = false;
}
