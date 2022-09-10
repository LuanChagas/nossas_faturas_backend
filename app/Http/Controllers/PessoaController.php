<?php

namespace App\Http\Controllers;

use App\Models\Pessoa;
use Illuminate\Http\Request;

class PessoaController extends Controller {
    public function index() {
        $pessoas = Pessoa::all('*');
        return $pessoas;
    }

    public function criar(Request $request) {
        $pessoa = new Pessoa($request->nome);
        $pessoa->save();
    }
}
