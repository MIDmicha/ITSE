<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SubsanacionController extends Controller
{
    /**
     * Muestra el formulario de verificación o, si ya está verificado, el formulario de corrección.
     */
    public function formulario($codigo)
    {
        $solicitud = Solicitud::where('codigo_seguimiento', $codigo)->firstOrFail();

        $verificado = session('subsanacion_verificado_' . $codigo, false);

        return view('solicitudes.subsanar', compact('solicitud', 'verificado'));
    }

    /**
     * Verifica identidad por DNI/RUC o correo antes de permitir editar.
     */
    public function verificar(Request $request, $codigo)
    {
        $solicitud = Solicitud::where('codigo_seguimiento', $codigo)->firstOrFail();

        $request->validate([
            'verificacion' => 'required|string',
        ]);

        $valor = trim($request->verificacion);

        $coincide = $valor === $solicitud->dni_ruc
            || strcasecmp($valor, $solicitud->email) === 0;

        if (!$coincide) {
            return back()->withErrors([
                'verificacion' => 'El DNI/RUC o correo no coincide con los datos de esta solicitud.',
            ]);
        }

        session(['subsanacion_verificado_' . $codigo => true]);

        return redirect()->route('solicitudes.subsanar', $codigo);
    }
    /**
 * Regresa al paso de verificación, olvidando que ya estaba verificado.
 */
            public function volverVerificacion($codigo)
            {
                session()->forget('subsanacion_verificado_' . $codigo);

                return redirect()->route('solicitudes.subsanar', $codigo);
            }
    /**
     * Guarda la corrección enviada por el ciudadano.
     */
    public function guardar(Request $request, $codigo)
    {
        $solicitud = Solicitud::where('codigo_seguimiento', $codigo)->firstOrFail();

        if (!session('subsanacion_verificado_' . $codigo)) {
            abort(403, 'Debes verificar tu identidad primero.');
        }

        $rules = [
            'nombres_solicitante' => 'required|min:3',
            'telefono_whatsapp'   => 'required|numeric|digits:9',
            'email'               => 'nullable|email',
        ];

        if ($solicitud->tipo_certificado !== 'evento_publico') {
            $rules['nombre_comercial'] = 'required';
            $rules['direccion']        = 'required';
        } else {
            $rules['nombre_evento']      = 'required';
            $rules['organizador_nombre'] = 'required';
            $rules['organizador_dni']    = 'required';
        }

        $rules['doc_solicitud']        = 'nullable|file|max:10240|mimes:pdf,jpg,jpeg,png';
        $rules['doc_plano']            = 'nullable|file|max:10240|mimes:pdf,jpg,jpeg,png';
        $rules['doc_dni_copia']        = 'nullable|file|max:10240|mimes:pdf,jpg,jpeg,png';
        $rules['doc_comprobante_pago'] = 'nullable|file|max:10240|mimes:pdf,jpg,jpeg,png';
        $rules['doc_otros']            = 'nullable|file|max:10240|mimes:pdf,jpg,jpeg,png';

        $request->validate($rules);

        $datos = $request->only([
            'nombres_solicitante',
            'telefono_whatsapp',
            'email',
            'nombre_comercial',
            'nombre_evento',
            'direccion',
            'organizador_nombre',
            'organizador_dni',
        ]);

        foreach (['doc_solicitud', 'doc_plano', 'doc_dni_copia', 'doc_comprobante_pago', 'doc_otros'] as $campo) {
            if ($request->hasFile($campo)) {
                $datos[$campo] = $request->file($campo)->store('solicitudes', 'public');
                Log::info("Subsanación: {$campo} actualizado para {$codigo}");
            }
        }

        $datos['estado'] = 'en_revision';
        $datos['observaciones'] = null;

        $solicitud->update($datos);

        session()->forget('subsanacion_verificado_' . $codigo);

        return redirect()->route('solicitudes.confirmacion', $codigo)
            ->with('success', '✅ Tu corrección fue enviada correctamente. Será revisada nuevamente por nuestro equipo.');
    }
}