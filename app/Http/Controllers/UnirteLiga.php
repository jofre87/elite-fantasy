<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UnirteLiga extends Controller
{
    /**
     * Procesa el código de liga enviado desde el formulario.
     */
    public function store(Request $request)
    {
        $request->validate([
            'league_code' => ['required', 'regex:/^[A-Z0-9]{3}-[A-Z0-9]{3}$/'],
        ]);

        $code = $request->input('league_code');

        // Aquí puedes realizar lógica adicional, como buscar una liga por el código, etc.

        return redirect()
            ->route('unir.form')
            ->with('success', 'Código recibido correctamente: ' . $code);
    }
}
