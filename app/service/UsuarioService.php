<?php

namespace App\service;

use App\DTOs\UsuarioDTO;
use App\Models\Usuario;
use App\repository\UsuarioRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use PDOException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class UsuarioService {
    private $usuarioRepository;

    public function __construct() {
        $this->usuarioRepository = new UsuarioRepository();
    }

    public function buscarUsuarios(): Response {
        try {
            $response = $this->usuarioRepository->buscarUsuarios();
            return response($response)
                ->header('Content-Type', 'application/json');
        } catch (PDOException $th) {
            return response(["mensagem" => $th->getMessage()], 500)
                ->header('Content-Type', 'application/problem');
        }
    }

    public function criarUsuario(Request $request): Response {
        try {
            $usuarioDTO = new UsuarioDTO($request);
            $usuario = new Usuario($usuarioDTO);
            $this->usuarioRepository->criarusuario($usuario);
            return response(["mensagem" => "usuario criado com sucesso"], 201)
                ->header('Content-Type', 'application/json');
        } catch (PDOException $th) {
            return response(["mensagem" => $th->getMessage()], 500)
                ->header('Content-Type', 'application/problem');
        } catch (BadRequestException $th) {
            return response(["mensagem" => $th->getMessage()], 400)
                ->header('Content-Type', 'application/problem');
        }
    }
}
