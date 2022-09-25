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
    private $id_cartao_data;

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
            $this->setIdCartaoData($fatura->id_cartao_data);
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

    public function setValor($valor) {
        $identificador = "Valor";
        $rs = Validacao::validarDecimal($valor, $identificador);
        if ($rs["resultado"]) {
            $this->valor = $valor;
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

    public function getIdCartaoData(): Int {
        return $this->id_cartao_data;
    }

    public function setIdCartaoData($id_cartao_data) {
        $identificador = "Identificador único da fatura";
        $rs = Validacao::validarInteiro(intval($id_cartao_data), $identificador);
        if ($rs["resultado"]) {
            $this->id_cartao_data = $id_cartao_data;
            return;
        }
        throw new BadRequestException($rs["mensagem"]);
    }
}