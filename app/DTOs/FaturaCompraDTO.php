<?php

namespace App\DTOs;

use App\Utils\Validacao;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class FaturaCompraDTO {
    private $id;
    private $id_fatura;
    private $id_compra;
    private $parcela;
    private $valor;

    public function __construct(?object $obj = null) {
        if (!is_null($obj)) {
            if (is_null($obj->id)) {
                $this->setId($obj->id);
            }
            $this->setIdFatura($obj->id_fatura);
            $this->setIdCompra($obj->id_compra);
            $this->setParcela($obj->parcela);
            $this->setValor($obj->valor);
        }
    }

    public function getId() {
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

    public function getIdFatura() {
        return $this->id_fatura;
    }


    public function setIdFatura($id_fatura) {
        $identificador = "id fatura";
        $rs = Validacao::validarInteiro($id_fatura, $identificador);
        if ($rs["resultado"]) {
            $this->id_fatura = $id_fatura;
            return;
        }
        throw new BadRequestException($rs["mensagem"]);
    }


    public function getIdCompra() {
        return $this->id_compra;
    }


    public function setIdCompra($id_compra) {
        $identificador = "id compra";
        $rs = Validacao::validarInteiro($id_compra, $identificador);
        if ($rs["resultado"]) {
            $this->id_compra = $id_compra;
            return;
        }
        throw new BadRequestException($rs["mensagem"]);
    }

    public function getParcela() {
        return $this->parcela;
    }

    public function setParcela($parcela) {
        $identificador = "Descrição";
        $rs = Validacao::validarNome($parcela, $identificador);
        if ($rs["resultado"]) {
            $this->parcela = $parcela;
            return;
        }
        throw new BadRequestException($rs["mensagem"]);
    }


    public function getValor() {
        return $this->valor;
    }
    public function setValor($valor) {
        $identificador = "Valor";
        $rs = Validacao::validarDecimal((float)$valor, $identificador);
        if ($rs["resultado"]) {
            $this->valor = (float)$valor;
            return;
        }
        throw new BadRequestException($rs["mensagem"]);
    }
}
