<?php

namespace App\DTOs;

use App\Utils\Validacao;
use PhpParser\Node\Expr\Cast\Double;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class CartaoDTO {

    private $id;
    private $nome;
    private $pix;
    private $dia_fechamento;
    private $dia_vencimento;
    private $limite_total;
    private $limite_parcial;

    public function __construct(?object $obj = null) {
        if ($obj) {
            if ($obj->id) {
                $this->id = $obj->id;
            }
            if ($obj->limite_parcial) {
                $this->setLimiteParcial($obj->limite_parcial);
            }
            $this->setNome($obj->nome);
            $this->setPix($obj->pix);
            $this->setDiaFechamento($obj->dia_fechamento);
            $this->setDiaVencimento($obj->dia_vencimento);
            $this->setLimiteTotal($obj->limite_total);
            $this->setLimiteParcial($obj->limite_total);

        }
    }

    public function getId(): Int|null {
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

    public function getNome(): String {
        return $this->nome;
    }

    public function setNome($nome) {
        $identificador = "Nome";
        $rs = Validacao::validarNome($nome, $identificador);
        if ($rs["resultado"]) {
            $this->nome = $nome;
            return;
        }
        throw new BadRequestException($rs["mensagem"]);
    }

    public function getPix(): String {
        return $this->pix;
    }

    public function setPix($pix) {
        $identificador = "Pix";
        $rs = Validacao::validarNome($pix, $identificador);
        if ($rs["resultado"]) {
            $this->pix = $pix;
            return;
        }
        throw new BadRequestException($rs["mensagem"]);
    }

    public function getDiaFechamento(): int {
        return $this->dia_fechamento;
    }

    public function setDiaFechamento($dia_fechamento) {
        $identificador = "Dia do fechamento";
        $rs = Validacao::validarInteiro($dia_fechamento, $identificador);
        if ($rs["resultado"]) {
            $this->dia_fechamento = $dia_fechamento;
            return;
        }
        throw new BadRequestException($rs["mensagem"]);
    }

    public function getDiaVencimento(): Int {
        return $this->dia_vencimento;
    }

    public function setDiaVencimento($dia_vencimento) {
        $identificador = "Dia do vencimento";
        $rs = Validacao::validarInteiro($dia_vencimento, $identificador);
        if ($rs["resultado"]) {
            $this->dia_vencimento = $dia_vencimento;
            return;
        }
        throw new BadRequestException($rs["mensagem"]);
    }

    public function getLimiteTotal(): float {
        return $this->limite_total;
    }

    public function setLimiteTotal(float $limite_total) {
        $identificador = "Limite total";
        $rs = Validacao::validarDecimal($limite_total, $identificador);
        if ($rs["resultado"]) {
            $this->limite_total = $limite_total;
            return;
        }
        throw new BadRequestException($rs["mensagem"]);
    }

    public function getLimiteParcial() {
        return $this->limite_parcial;
    }

    public function setLimiteParcial( float $limite_parcial) {
        
        $identificador = "Limite parcial";
        $rs = Validacao::validarDecimal($limite_parcial, $identificador);
        
        if ($rs["resultado"]) {
            $this->limite_parcial = $limite_parcial;
            return;
        }
        throw new BadRequestException($rs["mensagem"]);
    }

    public function objecToArray() {
        return [
            "id" => $this->id,
            "nome" => $this->getNome(),
            "pix" => $this->getPix(),
            "dia_fechamento" => $this->getDiaFechamento(),
            "dia_vencimento" => $this->getDiaVencimento(),
            "limite_total" => $this->getLimiteTotal(),
            "limite_parcial" => $this->getLimiteParcial()
        ];
    }
}
