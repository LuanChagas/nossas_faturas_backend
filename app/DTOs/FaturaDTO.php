<?php

namespace App\DTOs;

use App\Utils\Validacao;
use DateTime;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class FaturaDTO {

    private $id;
    private $valor;
    private $data;
    private $isFechada;
    private $isPaga;
    private $id_cartao;
    private $identificador;

    public function __construct(object $fatura = null) {
        if ($fatura) {
            if ($fatura->id) {
                $this->id = $fatura->id;
            }
            $this->setValor($fatura->valor);
            $this->setData($fatura->data);
            $this->setIsFechada($fatura->isFechada);
            $this->setIsPaga($fatura->isPaga);
            $this->setIdCartao($fatura->id_cartao);
            $this->setIdentificador($fatura->identificador);
        }
    }

    public function getId(): Int {
        return $this->id;
    }

    public function setId($id) {
        $identificador = "Id";
        $rs = Validacao::validarInteiro($id, $identificador);
        if ($rs["resultado"]) {
            $this->id = $id;
            return;
        }
        throw new BadRequestException($rs["mensagem"]);
    }

    public function getValor() {
        return $this->valor;
    }

    public function setValor(float $valor) {
        $identificador = "Valor";
        $rs = Validacao::validarDecimal($valor, $identificador);
        if ($rs["resultado"]) {
            $this->valor += $valor;
            return;
        }
        throw new BadRequestException($rs["mensagem"]);
    }

    public function getData(): DateTime {
        return $this->data;
    }

    public function setData($data) {
        $identificador = "Data";
        $rs = Validacao::validarData($data, $identificador);
        if ($rs["resultado"]) {
            $this->data = new DateTime($data);
            return;
        }
        throw new BadRequestException($rs["mensagem"]);
    }
    public function getIsFechada(): bool {
        return $this->isFechada;
    }

    public function setIsFechada($isFechada) {
        if ($isFechada == 0) {
            $isFechada = false;
        }
        $identificador = "Fechado";
        $rs = Validacao::validarBool($isFechada, $identificador);
        if ($rs["resultado"]) {
            $this->isFechada = $isFechada;
            return;
        }
        throw new BadRequestException($rs["mensagem"]);
    }

    public function getIsPaga(): bool {
        return $this->isPaga;
    }

    public function setIsPaga($isPaga) {
        if ($isPaga == 0) {
            $isPaga = false;
        }
        $identificador = "Pago";
        $rs = Validacao::validarBool($isPaga, $identificador);
        if ($rs["resultado"]) {
            $this->isPaga = $isPaga;
            return;
        }
        throw new BadRequestException($rs["mensagem"]);
    }

    public function getIdCartao(): Int {
        return $this->id_cartao;
    }

    public function setIdCartao($id_cartao) {
        $identificador = "ID do Cartão";
        $rs = Validacao::validarInteiro($id_cartao, $identificador);
        if ($rs["resultado"]) {
            $this->id_cartao = $id_cartao;
            return;
        }
        throw new BadRequestException($rs["mensagem"]);
    }

    public function getIdentificador(): Int {
        return $this->identificador;
    }

    public function setIdentificador($identificador) {
        $identificador = "Identificador único da fatura";
        $rs = Validacao::validarInteiro(intval($identificador), $identificador);
        if ($rs["resultado"]) {
            $this->identificador = $identificador;
            return;
        }
        throw new BadRequestException($rs["mensagem"]);
    }

    public function objectToArray() {
        return [
            "id" => $this->getId(),
            "valor" => $this->getValor(),
            "data" => $this->getData()->format('Y-m-d'),
            "isFechada" => $this->getIsFechada(),
            "isPaga" => $this->getIsPaga(),
            "id_cartao" => $this->getIdCartao(),
            "identificador" => $this->getIdentificador(),
        ];
    }
}
