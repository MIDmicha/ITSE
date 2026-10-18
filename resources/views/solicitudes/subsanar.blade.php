<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Corregir trámite | Trámite ITSE online M.A.A.C.D.</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/fontawesome.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/logo_muni.png') }}">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif !important; }
        .fa, .fas, .far, .fab { font-family: var(--fa-style-family,"Font Awesome 6 Free") !important; }
        :root {
            --green-main: #12961d;
            --green-dark: #0f2812;
        }
        body {
            background: linear-gradient(180deg, var(--green-main) 0%, var(--green-dark) 100%);
            min-height: 100vh;
            padding: 30px 0;
        }
        .card-subsanar {
            max-width: 720px;
            margin: 40px auto;
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.25);
            overflow: hidden;
        }
        .card-subsanar .card-header {
            background: var(--green-main);
            color: #fff;
            padding: 24px 30px;
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .card-subsanar .card-header img {
            width: 56px;
            height: 56px;
            border-radius: 10px;
            background: #fff;
            padding: 4px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .card-subsanar .card-body { padding: 30px; background: #fff; }
        .btn-brand {
            background: var(--green-main);
            color: #fff;
            border: none;
        }
        .btn-brand:hover { background: var(--green-dark); color: #fff; }
        .form-label { font-weight: 600; }
        .doc-actual {
            font-size: 0.85rem;
            color: #6b7280;
        }
        input.form-control:focus {
            border-color: var(--green-main);
            box-shadow: 0 0 0 3px rgba(18, 150, 29, 0.12);
            outline: none;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card card-subsanar">
        <div class="card-header">
            <img src="{{ asset('images/logo_muni.png') }}" alt="Logo Municipalidad">
            <div>
                <h4 class="mb-0"><i class="fas fa-file-circle-check me-2"></i>Corregir mi trámite</h4>
                <small>Código: {{ $solicitud->codigo_seguimiento }}</small>
            </div>
        </div>
        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @if (!$verificado)
                {{-- PASO 1: Verificación de identidad --}}
                <p class="text-muted">Para proteger tus datos, primero verifica tu identidad con tu DNI/RUC o el correo con el que enviaste la solicitud.</p>

                    <form method="POST" action="{{ route('solicitudes.subsanar.verificar', $solicitud->codigo_seguimiento) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">DNI/RUC o correo electrónico</label>
                        <input type="text" name="verificacion" class="form-control form-control-lg" required autofocus>
                    </div>
                    <button type="submit" class="btn btn-brand btn-lg w-100">
                        <i class="fas fa-unlock me-2"></i>Verificar identidad
                    </button>
                </form>
                <a href="{{ route('solicitudes.seguimiento', ['codigo' => $solicitud->codigo_seguimiento]) }}" class="btn btn-link w-100 mt-2 text-muted">
                    <i class="fas fa-arrow-left me-1"></i>Volver a seguimiento
                </a>

            @else
                {{-- PASO 2: Formulario de corrección --}}
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if ($solicitud->observaciones)
                    <div class="alert alert-warning">
                        <strong><i class="fas fa-triangle-exclamation me-1"></i>Observaciones del revisor:</strong><br>
                        {{ $solicitud->observaciones }}
                    </div>
                @endif

                <form method="POST" action="{{ route('solicitudes.subsanar.guardar', $solicitud->codigo_seguimiento) }}" enctype="multipart/form-data">
                    @csrf

                    <h6 class="mb-3" style="color: var(--green-main);">Datos del solicitante</h6>
                    <div class="mb-3">
                        <label class="form-label">Nombres completos</label>
                        <input type="text" name="nombres_solicitante" class="form-control" value="{{ old('nombres_solicitante', $solicitud->nombres_solicitante) }}" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">WhatsApp</label>
                            <input type="text" name="telefono_whatsapp" class="form-control" value="{{ old('telefono_whatsapp', $solicitud->telefono_whatsapp) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Correo electrónico</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $solicitud->email) }}">
                        </div>
                    </div>

                    @if ($solicitud->tipo_certificado !== 'evento_publico')
                        <h6 class="mb-3 mt-4" style="color: var(--green-main);">Datos del local</h6>
                        <div class="mb-3">
                            <label class="form-label">Nombre comercial</label>
                            <input type="text" name="nombre_comercial" class="form-control" value="{{ old('nombre_comercial', $solicitud->nombre_comercial) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Dirección</label>
                            <input type="text" name="direccion" class="form-control" value="{{ old('direccion', $solicitud->direccion) }}" required>
                        </div>
                    @else
                        <h6 class="mb-3 mt-4" style="color: var(--green-main);">Datos del evento</h6>
                        <div class="mb-3">
                            <label class="form-label">Nombre del evento</label>
                            <input type="text" name="nombre_evento" class="form-control" value="{{ old('nombre_evento', $solicitud->nombre_evento) }}" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Nombre del organizador</label>
                                <input type="text" name="organizador_nombre" class="form-control" value="{{ old('organizador_nombre', $solicitud->organizador_nombre) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">DNI del organizador</label>
                                <input type="text" name="organizador_dni" class="form-control" value="{{ old('organizador_dni', $solicitud->organizador_dni) }}" required>
                            </div>
                        </div>
                    @endif

                    <h6 class="mb-3 mt-4" style="color: var(--green-main);">Documentos</h6>
                    <p class="doc-actual mb-3">Solo sube un archivo si necesitas reemplazar el actual. Formatos: PDF, JPG, PNG (máx. 10MB).</p>

                    @php
                        $docs = [
                            'doc_solicitud' => 'Solicitud firmada',
                            'doc_plano' => 'Plano / croquis',
                            'doc_dni_copia' => 'Copia de DNI',
                            'doc_comprobante_pago' => 'Comprobante de pago',
                            'doc_otros' => 'Otros documentos',
                        ];
                    @endphp

                    @foreach ($docs as $campo => $label)
                        <div class="mb-3">
                            <label class="form-label">{{ $label }}</label>
                            <input type="file" name="{{ $campo }}" class="form-control">
                            @if ($solicitud->$campo)
                                <div class="doc-actual mt-1">
                                    <i class="fas fa-paperclip me-1"></i>Archivo actual guardado
                                </div>
                            @endif
                        </div>
                    @endforeach

                    <button type="submit" class="btn btn-brand btn-lg w-100 mt-3">
                        <i class="fas fa-paper-plane me-2"></i>Enviar corrección
                    </button>
                </form>
                <a href="{{ route('solicitudes.subsanar.volver', $solicitud->codigo_seguimiento) }}" class="btn btn-link w-100 mt-2 text-muted">
                    <i class="fas fa-arrow-left me-1"></i>Volver a verificación
                </a>
            @endif

        </div>
    </div>
</div>

</body>
</html>