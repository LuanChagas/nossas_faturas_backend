<?php

namespace App\DTOs;

use App\Utils\Validacao;
use Faker\Core\Number;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class UsuarioDTO {

    private $id;
    private $nome;

    public function __construct(?Object $usuario = null) {
        if (isset($usuario)) {
            if (isset($usuario->id)) {
                $this->setId($usuario->id);
            }
            $this->setNome($usuario->nome);
        }
    }

    public function getId(): Int {
        return $this->id;
    }

    public function setId(Int $id) {
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

    public function objectToArray() {
        return [
            "id" => $this->getId(),
            "valor" => $this->getNome(),
        ];
    }
}
