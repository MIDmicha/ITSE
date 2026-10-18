<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle Licencia - Municipalidad</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/fontawesome.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.jpg') }}">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        .fa, .fas, .far, .fa-classic, .fa-regular, .fa-solid, .fa-sharp { font-family: var(--fa-style-family,"Font Awesome 6 Free") !important; }
        .fab, .fa-brands { font-family: "Font Awesome 6 Brands" !important; }

        :root {
            --green-main: #12961d;
            --green-dark: #0f2812;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
        }

        body {
            background: linear-gradient(180deg, var(--green-main) 0%, var(--green-dark) 100%);
            min-height: 100vh;
            padding: 40px 0;
        }

        .header-consulta {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
            margin-bottom: 25px;
        }
        .header-consulta img {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            background: #fff;
            padding: 5px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .header-consulta h4 {
            font-family: 'Poppins', sans-serif;
            color: #fff;
            font-size: 18px;
            font-weight: 700;
            margin: 0;
            text-align: left;
        }

        .detail-card {
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 40px rgba(0,0,0,0.25);
            overflow: hidden;
        }

        .detail-card .card-header {
            background: var(--green-main);
            color: #fff;
            text-align: center;
            padding: 20px;
        }
        .detail-card .card-header h5 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            margin-bottom: 4px;
        }
        .detail-card .card-header h3 {
            font-family: 'SF Mono', 'Fira Code', monospace;
            font-weight: 700;
            letter-spacing: 0.03em;
            margin: 0;
        }

        .badge-pendiente { background-color: #ffc107; color: #000; }
        .badge-aceptado { background-color: var(--green-main); }
        .badge-aprobado { background-color: var(--green-main); }
        .badge-rechazado { background-color: #dc3545; }
        .badge-suspendido { background-color: #6c757d; }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid var(--border);
            font-size: 0.9rem;
        }
        .info-row:last-child { border-bottom: none; }
        .info-row span.label {
            color: var(--text-muted);
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }
        .info-row strong {
            color: var(--text-main);
            text-align: right;
        }

        .btn-institucional {
            background: var(--green-main);
            color: #fff;
            border: none;
            font-weight: 600;
        }
        .btn-institucional:hover { background: var(--green-dark); color: #fff; }
        .btn-outline-institucional {
            background: transparent;
            border: 1.5px solid var(--border);
            color: var(--text-main);
            font-weight: 600;
        }
        .btn-outline-institucional:hover { border-color: var(--green-main); color: var(--green-main); }

        .footer-links a {
            color: rgba(255,255,255,0.9) !important;
            border: 1.5px solid rgba(255,255,255,0.5) !important;
            font-weight: 600;
        }
        .footer-links a:hover {
            background: rgba(255,255,255,0.15);
            border-color: #fff !important;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="header-consulta">
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo Municipalidad">
                <h4>Municipalidad Distrital de<br>Andrés Avelino Cáceres Dorregaray</h4>
            </div>

            <div class="card detail-card mb-4">
                <div class="card-header">
                    <h5><i class="fas fa-file-alt me-2"></i>Licencia de Funcionamiento</h5>
                    <h3>{{ $licencia->numero_licencia }}</h3>
                </div>
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <span class="badge badge-{{ $licencia->estado }} fs-6 px-4 py-2">
                            <i class="fas fa-circle me-2" style="font-size:8px; vertical-align:middle;"></i>
                            {{ strtoupper($licencia->estado) }}
                        </span>
                    </div>

                    <div class="info-row">
                        <span class="label"><i class="fas fa-store me-2 text-success"></i>Nombre Comercial</span>
                        <strong>{{ $licencia->nombre_comercial }}</strong>
                    </div>
                    <div class="info-row">
                        <span class="label"><i class="fas fa-user me-2 text-success"></i>Titular</span>
                        <strong>{{ $licencia->contribuyente->nombres_razon_social }}</strong>
                    </div>
                    <div class="info-row">
                        <span class="label"><i class="fas fa-id-card me-2 text-success"></i>DNI/RUC</span>
                        <strong>{{ $licencia->contribuyente->dni_ruc }}</strong>
                    </div>
                    <div class="info-row">
                        <span class="label"><i class="fas fa-briefcase me-2 text-success"></i>Actividad</span>
                        <strong>{{ $licencia->actividadEconomica->descripcion }}</strong>
                    </div>
                    <div class="info-row">
                        <span class="label"><i class="fas fa-map-marker-alt me-2 text-success"></i>Dirección</span>
                        <strong>{{ $licencia->direccion_establecimiento }}</strong>
                    </div>
                    @if($licencia->fecha_emision)
                    <div class="info-row">
                        <span class="label"><i class="fas fa-calendar me-2 text-success"></i>Fecha Emisión</span>
                        <strong>{{ \Carbon\Carbon::parse($licencia->fecha_emision)->format('d/m/Y') }}</strong>
                    </div>
                    @endif
                    @if($licencia->fecha_vencimiento)
                    <div class="info-row">
                        <span class="label"><i class="fas fa-calendar-times me-2 text-success"></i>Vencimiento</span>
                        <strong>{{ \Carbon\Carbon::parse($licencia->fecha_vencimiento)->format('d/m/Y') }}</strong>
                    </div>
                    @endif
                </div>
                <div class="card-footer bg-white text-center py-3">
                <div class="d-flex gap-2 justify-content-center flex-wrap">
                    <a href="{{ route('solicitudes.seguimiento') }}" class="btn btn-outline-institucional">
                        <i class="fas fa-arrow-left me-1"></i>Volver
                    </a>
                    @if($licencia->estado === 'aprobado')
                    <a href="{{ route('consulta.pdf', $licencia->id) }}" class="btn btn-institucional">
                        <i class="fas fa-file-pdf me-1"></i>Descargar Certificado PDF
                    </a>
                    @endif
                    <a href="{{ route('solicitudes.formulario') }}" class="btn btn-outline-institucional">
                        <i class="fas fa-redo me-1"></i>Nueva gestión
                    </a>
                </div>
            </div>
            </div>

            <div class="text-center" style="font-size:12px; color: rgba(255,255,255,0.7);">
                <p>© {{ date('Y') }} Municipalidad Distrital de Andrés Avelino Cáceres Dorregaray</p>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>