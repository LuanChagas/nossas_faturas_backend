<?php


namespace App\Utils;


class Validacao {

    static public function validarInteiro($valor, $identificador): array {
        $mensagem = "";
        if (isset($valor)) {
            if (is_int($valor)) {
                return [
                    "resultado" => true
                ];
            } else {
                $mensagem = "$identificador não é um formato inteiro";
            }
        } else {
            $mensagem = "$identificador é vazio ";
        }
        return [
            "resultado" => false,
            "mensagem" => $mensagem
        ];
    }

    static public function validarNome($valor, $identificador, $isPessoal = false): array {
        $mensagem = "";
        if (isset($valor)) {
            if (is_string($valor)) {
                if (
                    $isPessoal &&
                    preg_match("/^[A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ ]+$/", $valor)
                ) {
                    return [
                        "resultado" => true
                    ];
                } else {
                    $mensagem = "$identificador não está em formato Válido";
                }
                return [
                    "resultado" => true
                ];
            } else {
                $mensagem = "$identificador não está em formato Válido";
            }
        } else {
            $mensagem = "$identificador não pode ser vazio";
        }
        return [
            "resultado" => false,
            "mensagem" => $mensagem
        ];
    }

    static public function validarDecimal($valor, $identificador): array {
        $mensagem = "";
        if (isset($valor)) {
            if (is_float($valor)) {
                return [
                    "resultado" => true
                ];
            } else {
                $mensagem = "$identificador não está em formato Válido";
            }
        } else {
            $mensagem = "$identificador não pode ser vazio";
        }
        return [
            "resultado" => false,
            "mensagem" => $mensagem
        ];
    }
    static public function validarData($valor, $identificador): array {
        $mensagem = "";
        if (isset($valor)) {
            if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $valor)) {
                return [
                    "resultado" => true
                ];
            } else {
                $mensagem = "$identificador não está em formato Válido";
            }
        } else {
            $mensagem = "$identificador não pode ser vazio";
        }
        return [
            "resultado" => false,
            "mensagem" => $mensagem
        ];
    }

    static public function validarBool($valor, $identificador): array {
        $mensagem = "";
        if (isset($valor)) {
            if (is_bool($valor)) {
                return [
                    "resultado" => true
                ];
            } else {
                $mensagem = "$identificador não está em formato Válido";
            }
        } else {
            $mensagem = "$identificador não pode ser vazio";
        }
        return [
            "resultado" => false,
            "mensagem" => $mensagem
        ];
    }
}
