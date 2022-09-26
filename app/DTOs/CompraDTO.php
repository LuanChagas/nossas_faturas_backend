<?php

namespace App\DTOs;

use App\Utils\Validacao;
use DateTime;
use NumberFormatter;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class CompraDTO {

    private $id;
    private $nome;
    private $descricao;
    private $data_compra;
    private $valor;
    private $parcelas;
    private $id_pessoa;
    private $id_cartao;

    public function __construct(?object $compra = null) {

        if ($compra) {
            if ($compra->id) {
                $this->setId($compra->id);
            }
            $this->setNome($compra->nome);
            $this->setDescricao($compra->descricao);
            $this->setDataCompra($compra->data_compra);
            $this->setValor($compra->valor);
            $this->setParcelas($compra->parcelas);
            $this->setIdPessoa($compra->id_pessoa);
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

    public function getNome(): String {
        return $this->nome;
    }

    public function setNome($nome) {
        $mensagem = "";
        if (isset($nome)) {
            $reg = "/^[A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ ]+$/";
            if (preg_match($reg, $nome)) {
                $this->nome = $nome;
                return;
            } else {
                $mensagem = "Nome não está em formato Válido";
            }
        } else {
            $mensagem = "Nome não pode ser vazio";
        }
        throw new BadRequestException($mensagem);
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
        $mensagem = "";
        if (isset($data_compra)) {
            if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $data_compra)) {
                $this->data_compra = new DateTime($data_compra);
                return;
            } else {
                $mensagem = "Formato invalido data da compra";
            }
        } else {
            $mensagem = "Data da compra não pode ser vazio ";
        }
        throw new BadRequestException($mensagem);
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

    public function getIdPessoa(): Int {
        return $this->id_pessoa;
    }

    public function setIdPessoa($id_pessoa) {
        $mensagem = "";
        if (isset($id_pessoa)) {
            if (is_int($id_pessoa)) {
                $this->id_pessoa = $id_pessoa;
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
            "nome" => $this->getNome(),
            "descricao" => $this->getDescricao(),
            "data_compra" => $this->getDataCompra()->format("Y-m-d"),
            "valor" => $this->getValor(),
            "parcelas" => $this->getParcelas(),
            "id_pessoa" => $this->getIdPessoa(),
            "id_cartao" => $this->getIdCartao(),
        ];
    }
}
