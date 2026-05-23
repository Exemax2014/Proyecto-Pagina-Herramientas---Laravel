<?php

namespace App\Http\Controllers;

use App\Models\Usuario;

class PerfilController extends Controller
{
    public function misDatos()
    {
        $usuario = Usuario::findOrFail(session('usuario_id'));

        return view('pages.mis-datos', compact('usuario'));
    }
}
