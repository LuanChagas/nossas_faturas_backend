<?php


namespace App\repository;

use App\Models\Usuario;
use Illuminate\Database\Eloquent\Collection;
use PDOException;

class UsuarioRepository {


    public function buscarUsuarios(): Collection {
        try {
            return Usuario::with('compras')->get(["*"]);
        } catch (PDOException $th) {
            return throw new PDOException(
                "Erro ao buscar Usuarios. Código: " . $th->getMessage()
            );
        }
    }
    public function criarUsuario(Usuario $usuario) {
        try {
            $usuario->save();
        } catch (PDOException $th) {
            return throw new PDOException(
                "Erro ao criar Usuario. Código: " . $th->getCode()
            );
        }
    }
}
