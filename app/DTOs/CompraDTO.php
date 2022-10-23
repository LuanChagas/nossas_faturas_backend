<?php

namespace App\DTOs;

use App\Utils\Validacao;
use DateTime;
use NumberFormatter;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class CompraDTO {

    private $id;
    private $descricao;
    private $local;
    private $data_compra;
    private $valor;
    private $parcelas;
    private $id_usuario;
    private $id_cartao;

    public function __construct(?object $compra = null) {

        if ($compra) {
            if ($compra->id) {
                $this->setId($compra->id);
            }
            $this->setLocal($compra->local);
            $this->setDescricao($compra->descricao);
            $this->setDataCompra($compra->data_compra);
            $this->setValor($compra->valor);
            $this->setParcelas($compra->parcelas);
            $this->setIdUsuario($compra->id_usuario);
            $this->setIdCartao($compra->id_cartao);
        }
    }
    public function getId(): Int {
        return $this->id;
    }

    public function setId($id) {
        $identificador = "id";
        $rs = Validacao::validarInteiro($id, $identificador);
        if ($rs["resultado"]) {
            $this->id = $id;
            return;
        }
        throw new BadRequestException($rs["mensagem"]);
    }

    public function getLocal(): String {
        return $this->local;
    }

    public function setLocal($local) {
        $identificador = "local";
        $rs = Validacao::validarNome($local, $identificador);
        if ($rs["resultado"]) {
            $this->local = $local;
            return;
        }
        throw new BadRequestException($rs["mensagem"]);
    }

    public function getDescricao(): String {
        return $this->descricao;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    public function getDataCompra(): DateTime {
        return $this->data_compra;
    }

    public function setDataCompra($data_compra) {
        $identificador = "Data da Compra";
        $rs = Validacao::validarData($data_compra, $identificador);
        if ($rs["resultado"]) {
            $this->data_compra = new DateTime($data_compra);
            return;
        }
        throw new BadRequestException($rs["mensagem"]);
    }

    public function getValor(): Float {
        return $this->valor;
    }

    public function setValor($valor) {
        $mensagem = "";
        if (isset($valor)) {
            if (is_float($valor)) {
                $this->valor = $valor;
                return;
            } else {
                $mensagem = "Formato invalido valor da compra";
            }
        } else {
            $mensagem = "valor da compra não pode ser vazio ";
        }
        throw new BadRequestException($mensagem);
    }

    public function getParcelas(): Int {
        return $this->parcelas;
    }

    public function setParcelas($parcelas) {
        $mensagem = "";
        if (isset($parcelas)) {
            if (is_int($parcelas)) {
                $this->parcelas = $parcelas;
                return;
            } else {
                $mensagem = "Parcelas não é um formato inteiro";
            }
        } else {
            $mensagem = "parcelas esta vazio ";
        }
        throw new BadRequestException($mensagem);
    }

    public function getIdUsuario(): Int {
        return $this->id_usuario;
    }

    public function setIdUsuario($id_usuario) {
        $mensagem = "";
        if (isset($id_usuario)) {
            if (is_int($id_usuario)) {
                $this->id_usuario = $id_usuario;
                return;
            } else {
                $mensagem = "id_pessoa não é um formato inteiro";
            }
        } else {
            $mensagem = "id_pessoa esta vazio ";
        }
        throw new BadRequestException($mensagem);
    }

    public function getIdCartao(): Int|null {
        return $this->id_cartao;
    }

    public function setIdCartao($id_cartao) {
        $mensagem = "";
        if (isset($id_cartao)) {
            if (is_int($id_cartao)) {
                $this->id_cartao = $id_cartao;
                return;
            } else {
                $mensagem = "id_cartao não é um formato inteiro";
            }
        } else {
            $mensagem = "id_cartao esta vazio ";
        }
        throw new BadRequestException($mensagem);
    }

    public function objectToArray() {
        return [
            "descricao" => $this->getDescricao(),
            "local" => $this->getlocal(),
            "data_compra" => $this->getDataCompra()->format("Y-m-d"),
            "valor" => $this->getValor(),
            "parcelas" => $this->getParcelas(),
            "id_usuario" => $this->getIdUsuario(),
            "id_cartao" => $this->getIdCartao(),
        ];
    }
}
