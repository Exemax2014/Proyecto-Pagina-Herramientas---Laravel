<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consulta;

class AdminConsultaController extends Controller
{
    public function index()
    {
        $consultas = Consulta::query()
            ->orderBy('leida')
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('admin.consultas.index', compact('consultas'));
    }

    public function marcarLeida(Consulta $consulta)
    {
        $consulta->update([
            'leida' => true,
        ]);

        return redirect()
            ->route('admin.consultas.index')
            ->with('success', 'Consulta marcada como leída.');
    }
}
