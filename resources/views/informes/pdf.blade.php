<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Informe Pericial — {{ $informe->oficio->numero_oficio }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #1a1a2e;
            background: #fff;
            padding: 0;
        }

        /* ── Encabezado ── */
        .header {
            background: #2c3e6b;
            color: white;
            padding: 22px 32px 18px;
            margin-bottom: 0;
        }
        .header-top {
            display: table;
            width: 100%;
        }
        .header-logo {
            display: table-cell;
            vertical-align: middle;
            width: 60px;
        }
        .logo-circle {
            width: 48px; height: 48px;
            border-radius: 50%;
            background: #7ec8a4;
            text-align: center;
            line-height: 48px;
            font-size: 20px;
            font-weight: 900;
            color: #2c3e6b;
        }
        .header-info {
            display: table-cell;
            vertical-align: middle;
        }
        .header-title {
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 0.05em;
        }
        .header-sub {
            font-size: 9px;
            color: rgba(255,255,255,0.6);
            letter-spacing: 0.12em;
            text-transform: uppercase;
            margin-top: 2px;
        }
        .header-right {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
            font-size: 9px;
            color: rgba(255,255,255,0.55);
        }
        .header-right strong {
            display: block;
            font-size: 13px;
            color: #7ec8a4;
            font-weight: 700;
        }

        /* ── Banda de título ── */
        .doc-title-band {
            background: #354880;
            color: white;
            padding: 10px 32px;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        /* ── Cuerpo ── */
        .body-wrap {
            padding: 24px 32px;
        }

        /* ── Sección ── */
        .section {
            margin-bottom: 18px;
        }
        .section-title {
            font-size: 8.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: #6b5fb5;
            border-bottom: 1.5px solid #e0dff8;
            padding-bottom: 4px;
            margin-bottom: 10px;
        }

        /* ── Grid de datos ── */
        .data-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }
        .data-row {
            display: table-row;
        }
        .data-label {
            display: table-cell;
            width: 38%;
            font-size: 9px;
            font-weight: 700;
            color: #6878a8;
            padding: 4px 8px 4px 0;
            vertical-align: top;
        }
        .data-value {
            display: table-cell;
            font-size: 10px;
            color: #1a1a2e;
            padding: 4px 0;
            vertical-align: top;
        }

        /* ── Tabla ── */
        table.info-table {
            width: 100%;
            border-collapse: collapse;
        }
        table.info-table td {
            padding: 5px 10px;
            font-size: 10px;
            border-bottom: 1px solid #eef0f8;
            vertical-align: top;
        }
        table.info-table td.lbl {
            width: 35%;
            font-weight: 700;
            color: #6878a8;
            font-size: 9px;
        }
        table.info-table tr:last-child td { border-bottom: none; }
        table.info-table.shaded tr:nth-child(odd) td { background: #f8f9fd; }

        /* ── Contenido del informe ── */
        .informe-box {
            background: #f8f9fd;
            border: 1px solid #e0dff8;
            border-left: 3px solid #6b5fb5;
            border-radius: 4px;
            padding: 14px 16px;
            font-size: 10.5px;
            line-height: 1.7;
            color: #1a1a2e;
            white-space: pre-wrap;
            word-break: break-word;
        }

        /* ── Firma ── */
        .firma-section {
            margin-top: 32px;
            display: table;
            width: 100%;
        }
        .firma-col {
            display: table-cell;
            width: 50%;
            text-align: center;
            padding: 0 20px;
        }
        .firma-line {
            border-top: 1.5px solid #2c3e6b;
            margin: 0 auto 6px;
            width: 80%;
        }
        .firma-name {
            font-size: 10px;
            font-weight: 700;
            color: #2c3e6b;
        }
        .firma-sub {
            font-size: 8.5px;
            color: #8090b8;
            margin-top: 2px;
        }

        /* ── Footer ── */
        .footer {
            position: fixed;
            bottom: 0; left: 0; right: 0;
            background: #f4f6fb;
            border-top: 1px solid #d4daf0;
            padding: 7px 32px;
            display: table;
            width: 100%;
        }
        .footer-left {
            display: table-cell;
            font-size: 8px;
            color: #8090b8;
            vertical-align: middle;
        }
        .footer-right {
            display: table-cell;
            text-align: right;
            font-size: 8px;
            color: #8090b8;
            vertical-align: middle;
        }

        /* ── Badge estado ── */
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 8.5px;
            font-weight: 700;
        }
        .badge-enviado { background: #d1fae5; color: #065f46; }
        .badge-pendiente { background: #fef3c7; color: #92400e; }
    </style>
</head>
<body>

{{-- ── Encabezado ── --}}
<div class="header">
    <div class="header-top">
        <div class="header-logo">
            <div class="logo-circle">S</div>
        </div>
        <div class="header-info">
            <div class="header-title">SIPSI</div>
            <div class="header-sub">Sistema de Psiquiatría Hospitalaria</div>
        </div>
        <div class="header-right">
            Generado el {{ \Carbon\Carbon::now()->format('d/m/Y') }}
            <strong>INFORME PERICIAL</strong>
        </div>
    </div>
</div>

<div class="doc-title-band">
    Informe Pericial — Oficio Nº {{ $informe->oficio->numero_oficio }}
</div>

<div class="body-wrap">

    {{-- ── Datos del oficio ── --}}
    <div class="section">
        <div class="section-title">Datos del oficio judicial</div>
        <table class="info-table shaded">
            <tr>
                <td class="lbl">Número de oficio</td>
                <td>{{ $informe->oficio->numero_oficio }}</td>
                <td class="lbl">Fecha de recepción</td>
                <td>{{ \Carbon\Carbon::parse($informe->oficio->fecha_recepcion)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td class="lbl">Juzgado</td>
                <td>{{ $informe->oficio->juzgado->nombre }}</td>
                <td class="lbl">Medio de recepción</td>
                <td>{{ ucfirst($informe->oficio->medio_recepcion) }}</td>
            </tr>
            <tr>
                <td class="lbl">Tipo de pedido</td>
                <td colspan="3">{{ $informe->oficio->tipo_pedido }}</td>
            </tr>
            @if($informe->oficio->observaciones)
            <tr>
                <td class="lbl">Observaciones</td>
                <td colspan="3">{{ $informe->oficio->observaciones }}</td>
            </tr>
            @endif
        </table>
    </div>

    {{-- ── Datos del paciente ── --}}
    <div class="section">
        <div class="section-title">Datos del paciente evaluado</div>
        <table class="info-table shaded">
            <tr>
                <td class="lbl">Apellido y nombre</td>
                <td>{{ $informe->oficio->paciente->apellido }}, {{ $informe->oficio->paciente->nombre }}</td>
                <td class="lbl">DNI</td>
                <td>{{ $informe->oficio->paciente->dni }}</td>
            </tr>
            @if($informe->oficio->paciente->fecha_nacimiento)
            <tr>
                <td class="lbl">Fecha de nacimiento</td>
                <td>{{ \Carbon\Carbon::parse($informe->oficio->paciente->fecha_nacimiento)->format('d/m/Y') }}</td>
                <td class="lbl">Teléfono</td>
                <td>{{ $informe->oficio->paciente->telefono ?? '—' }}</td>
            </tr>
            @endif
            @if($informe->oficio->paciente->direccion)
            <tr>
                <td class="lbl">Dirección</td>
                <td colspan="3">{{ $informe->oficio->paciente->direccion }}</td>
            </tr>
            @endif
        </table>
    </div>

    {{-- ── Datos del informe ── --}}
    <div class="section">
        <div class="section-title">Datos del informe</div>
        <table class="info-table shaded">
            <tr>
                <td class="lbl">Fecha del informe</td>
                <td>{{ \Carbon\Carbon::parse($informe->fecha_informe)->format('d/m/Y') }}</td>
                <td class="lbl">Profesional interviniente</td>
                <td>{{ $informe->profesional->apellido }}, {{ $informe->profesional->nombre }} — {{ $informe->profesional->especialidad }}</td>
            </tr>
            <tr>
                <td class="lbl">Estado de envío</td>
                <td colspan="3">
                    @if($informe->enviado_juzgado)
                        <span class="badge badge-enviado">Enviado al juzgado</span>
                        — {{ \Carbon\Carbon::parse($informe->fecha_envio)->format('d/m/Y') }}
                    @else
                        <span class="badge badge-pendiente">Pendiente de envío</span>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    {{-- ── Contenido del informe ── --}}
    <div class="section">
        <div class="section-title">Contenido del informe pericial</div>
        <div class="informe-box">{{ $informe->contenido }}</div>
    </div>

    {{-- ── Firma ── --}}
    <div class="firma-section">
        <div class="firma-col">
            <div class="firma-line"></div>
            <div class="firma-name">{{ $informe->profesional->apellido }}, {{ $informe->profesional->nombre }}</div>
            <div class="firma-sub">{{ $informe->profesional->especialidad }}</div>
            <div class="firma-sub">Profesional interviniente</div>
        </div>
        <div class="firma-col">
            <div class="firma-line"></div>
            <div class="firma-name">Servicio de Psiquiatría</div>
            <div class="firma-sub">SIPSI — Psiquiatría Hospitalaria</div>
        </div>
    </div>

</div>

{{-- ── Footer ── --}}
<div class="footer">
    <div class="footer-left">
        SIPSI — Sistema de Psiquiatría Hospitalaria · Documento generado el {{ \Carbon\Carbon::now()->format('d/m/Y \a \l\a\s H:i') }}hs
    </div>
    <div class="footer-right">
        Oficio Nº {{ $informe->oficio->numero_oficio }} · Confidencial
    </div>
</div>

</body>
</html>
