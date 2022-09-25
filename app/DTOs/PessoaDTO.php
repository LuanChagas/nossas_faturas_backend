<?php

namespace App\DTOs;

use App\Utils\Validacao;
use Faker\Core\Number;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class PessoaDTO {

    private $id;
    private $nome;

    public function __construct(?Object $pessoa = null) {
        if (isset($pessoa)) {
            if (isset($pessoa->id)) {
                $this->setId($pessoa->id);
            }
            $this->setNome($pessoa->nome);
        }
    }

    public function getId(): Number {
        return $this->id;
    }

    public function setId(Number $id) {
        $identificador = "id";
        $rs = Validacao::validarInteiro($id, $identificador);
        if ($rs["resultado"]) {
            $this->id = $id;
            return;
        }
        throw new BadRequestException($rs["mensagem"]);
    }

    public function getNome(): String {
        return $this->nome;
    }

    public function setNome(String $nome = null) {
        $identificador = "Nome";
        $rs = Validacao::validarNome($nome, $identificador, true);
        if ($rs["resultado"]) {
            $this->nome = $nome;
            return;
        }
        throw new BadRequestException($rs["mensagem"]);
    }
}
