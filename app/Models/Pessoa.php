<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model {


    public function __construct($nome) {
        $this->nome = $nome;
    }
    protected $table = 'PESSOAS';
    public $timestamps = false;
}
