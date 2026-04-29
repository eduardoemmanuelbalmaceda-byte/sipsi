<?php $__env->startSection('content'); ?>


<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="page-header-title mb-0">Oficio <?php echo e($oficio->numero_oficio); ?></h2>
        <p class="page-header-sub"><?php echo e($oficio->juzgado->nombre); ?> · <?php echo e($oficio->paciente->apellido); ?>, <?php echo e($oficio->paciente->nombre); ?></p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('oficios.edit', $oficio)); ?>" class="btn btn-warning btn-sm">Editar</a>
        <a href="<?php echo e(route('oficios.index')); ?>" class="btn btn-secondary btn-sm">Volver</a>
    </div>
</div>


<?php
    $paso = 1;
    if ($oficio->turno)              $paso = 2;
    if ($oficio->notificado_por)     $paso = 3;
    if ($oficio->confirmacion_juzgado) $paso = 4;
    if ($oficio->turno && $oficio->turno->asistencia !== 'pendiente') $paso = 5;
    if ($oficio->informe)            $paso = 6;
    if ($oficio->informe && ($oficio->informe->enviado_juzgado || $oficio->informe->enviado_direccion)) $paso = 7;
    if ($oficio->informe && $oficio->informe->enviado_juzgado && $oficio->informe->enviado_direccion)   $paso = 8;
    $pasos = [
        1 => 'Recepción',
        2 => 'Turno',
        3 => 'Notificación',
        4 => 'Confirmación',
        5 => 'Día del turno',
        6 => 'Informe',
        7 => 'Envío',
        8 => 'Cierre',
    ];
?>

<div class="card mb-4 p-3">
    <div style="display:flex; align-items:center; gap:0; overflow-x:auto;">
        <?php $__currentLoopData = $pasos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $num => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $done    = $paso >= $num;
                $current = $paso === $num;
            ?>
            <div style="display:flex; align-items:center; flex:1; min-width:60px;">
                <div style="display:flex; flex-direction:column; align-items:center; flex:1;">
                    <div style="
                        width:32px; height:32px; border-radius:50%;
                        background: <?php echo e($done ? 'linear-gradient(135deg,var(--mint),var(--green))' : 'var(--surface2)'); ?>;
                        border: 2px solid <?php echo e($current ? 'var(--green)' : ($done ? 'transparent' : 'var(--border)')); ?>;
                        display:flex; align-items:center; justify-content:center;
                        font-size:0.75rem; font-weight:700;
                        color: <?php echo e($done ? '#fff' : 'var(--text-muted)'); ?>;
                        box-shadow: <?php echo e($current ? '0 0 0 3px rgba(90,158,122,0.25)' : 'none'); ?>;
                        transition: all 0.3s;
                    ">
                        <?php if($done && !$current): ?>
                            <svg width="14" height="14" fill="none" stroke="white" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <?php else: ?>
                            <?php echo e($num); ?>

                        <?php endif; ?>
                    </div>
                    <span style="font-size:0.65rem; color:<?php echo e($done ? 'var(--green)' : 'var(--text-muted)'); ?>; font-weight:<?php echo e($current ? '700' : '500'); ?>; margin-top:4px; white-space:nowrap;">
                        <?php echo e($label); ?>

                    </span>
                </div>
                <?php if($num < count($pasos)): ?>
                    <div style="height:2px; flex:1; background:<?php echo e($paso > $num ? 'var(--mint)' : 'var(--border)'); ?>; margin-bottom:18px; transition:background 0.3s;"></div>
                <?php endif; ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>


<div class="row mb-4">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header fw-bold">Datos del oficio</div>
            <div class="card-body">
                <p><strong>Número:</strong> <?php echo e($oficio->numero_oficio); ?></p>
                <p><strong>Juzgado:</strong> <?php echo e($oficio->juzgado->nombre); ?></p>
                <p><strong>Fecha de recepción:</strong> <?php echo e(\Carbon\Carbon::parse($oficio->fecha_recepcion)->format('d/m/Y')); ?></p>
                <?php if($oficio->fecha_vencimiento): ?>
                <p><strong>Vencimiento:</strong> <?php echo e(\Carbon\Carbon::parse($oficio->fecha_vencimiento)->format('d/m/Y')); ?></p>
                <?php endif; ?>
                <p><strong>Medio:</strong> <?php echo e(ucfirst($oficio->medio_recepcion)); ?></p>
                <p><strong>Tipo de pedido:</strong> <?php echo e($oficio->tipo_pedido); ?></p>
                <p><strong>Estado:</strong>
                    <span class="badge bg-<?php echo e($oficio->estado == 'cerrado' ? 'success' : ($oficio->estado == 'en_curso' ? 'warning' : 'secondary')); ?>">
                        <?php echo e(ucfirst(str_replace('_', ' ', $oficio->estado))); ?>

                    </span>
                </p>
                <?php if($oficio->observaciones): ?>
                <p><strong>Observaciones:</strong> <?php echo e($oficio->observaciones); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header fw-bold">Paciente</div>
            <div class="card-body">
                <p><strong>Nombre:</strong> <?php echo e($oficio->paciente->apellido); ?>, <?php echo e($oficio->paciente->nombre); ?></p>
                <p><strong>DNI:</strong> <?php echo e($oficio->paciente->dni); ?></p>
                <p><strong>Teléfono:</strong> <?php echo e($oficio->paciente->telefono ?? '-'); ?></p>
                <p><strong>Dirección:</strong> <?php echo e($oficio->paciente->direccion ?? '-'); ?></p>
                <a href="<?php echo e(route('pacientes.show', $oficio->paciente)); ?>" class="btn btn-sm btn-info">Ver historial</a>
            </div>
        </div>
    </div>
</div>


<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong>
            <span style="color:var(--mint);">②</span> Turno asignado
        </strong>
        <?php if(!$oficio->turno): ?>
            <a href="<?php echo e(route('turnos.create', $oficio)); ?>" class="btn btn-sm btn-primary">+ Asignar turno</a>
        <?php else: ?>
            <div class="d-flex gap-1">
                <a href="<?php echo e(route('turnos.edit', $oficio->turno)); ?>" class="btn btn-sm btn-warning">Editar</a>
                <form action="<?php echo e(route('turnos.destroy', $oficio->turno)); ?>" method="POST"
                      onsubmit="return confirm('¿Eliminar este turno?')">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <?php if($oficio->turno): ?>
            <div class="row">
                <div class="col-md-3"><strong>Fecha:</strong> <?php echo e(\Carbon\Carbon::parse($oficio->turno->fecha_turno)->format('d/m/Y')); ?></div>
                <div class="col-md-3"><strong>Hora:</strong> <?php echo e(\Carbon\Carbon::parse($oficio->turno->hora)->format('H:i')); ?></div>
                <div class="col-md-4"><strong>Profesional:</strong> <?php echo e($oficio->turno->profesional->apellido); ?>, <?php echo e($oficio->turno->profesional->nombre); ?></div>
                <div class="col-md-2">
                    <strong>Estado:</strong>
                    <span class="badge bg-<?php echo e($oficio->turno->estado == 'realizado' ? 'success' : ($oficio->turno->estado == 'ausente' ? 'warning' : 'secondary')); ?>">
                        <?php echo e(ucfirst($oficio->turno->estado)); ?>

                    </span>
                </div>
            </div>
        <?php else: ?>
            <p class="text-muted mb-0">Sin turno asignado.</p>
        <?php endif; ?>
    </div>
</div>


<?php if($oficio->turno): ?>
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong><span style="color:var(--mint);">③</span> Notificación al paciente</strong>
        <?php if(!$oficio->notificado_por): ?>
            <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNotif">
                Registrar notificación
            </button>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <?php if($oficio->notificado_por): ?>
            <p class="mb-0">
                <strong>Notificado por:</strong>
                <span class="badge bg-info">
                    <?php echo e($oficio->notificado_por === 'direccion' ? 'Dirección' : ($oficio->notificado_por === 'juzgado' ? 'Juzgado' : 'Conflicto — informado al juzgado')); ?>

                </span>
            </p>
        <?php else: ?>
            <div class="collapse" id="collapseNotif">
                <form action="<?php echo e(route('oficios.notificacion', $oficio)); ?>" method="POST" class="mt-2">
                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                    <div class="mb-3">
                        <label class="form-label">¿Quién notifica al paciente?</label>
                        <div class="d-flex gap-3 flex-wrap">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="notificado_por" value="direccion" id="notifDir">
                                <label class="form-check-label" for="notifDir">Dirección</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="notificado_por" value="juzgado" id="notifJuz">
                                <label class="form-check-label" for="notifJuz">Juzgado</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="notificado_por" value="conflicto" id="notifConf">
                                <label class="form-check-label" for="notifConf">Conflicto — informar al juzgado</label>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary">Guardar</button>
                </form>
            </div>
            <?php if(!$oficio->notificado_por): ?>
                <p class="text-muted mb-0">Pendiente de registrar.</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>


<?php if($oficio->notificado_por): ?>
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong><span style="color:var(--mint);">④</span> Confirmación del juzgado</strong>
    </div>
    <div class="card-body">
        <?php if($oficio->confirmacion_juzgado): ?>
            <p class="mb-0">
                <span class="badge bg-success">✓ Confirmado el <?php echo e(\Carbon\Carbon::parse($oficio->fecha_confirmacion_juzgado)->format('d/m/Y')); ?></span>
            </p>
        <?php else: ?>
            <div class="d-flex align-items-center gap-3">
                <p class="text-muted mb-0">Esperando confirmación del juzgado.</p>
                <form action="<?php echo e(route('oficios.confirmarJuzgado', $oficio)); ?>" method="POST">
                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                    <button type="submit" class="btn btn-sm btn-success"
                            onclick="return confirm('¿Confirmar recepción del juzgado?')">
                        Marcar como confirmado
                    </button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>


<?php if($oficio->turno): ?>
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong><span style="color:var(--mint);">⑤</span> Día del turno — ¿El paciente asistió?</strong>
    </div>
    <div class="card-body">
        <?php if($oficio->turno->asistencia === 'asistio'): ?>
            <span class="badge bg-success">✓ El paciente asistió</span>
        <?php elseif($oficio->turno->asistencia === 'no_asistio'): ?>
            <span class="badge bg-warning">✗ El paciente no asistió</span>
            <?php if($oficio->turno->motivo_inasistencia): ?>
                <p class="mt-2 mb-0"><strong>Motivo:</strong> <?php echo e($oficio->turno->motivo_inasistencia); ?></p>
            <?php endif; ?>
        <?php else: ?>
            <form action="<?php echo e(route('turnos.asistencia', $oficio->turno)); ?>" method="POST">
                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                <div class="row align-items-end g-3">
                    <div class="col-md-4">
                        <label class="form-label">Asistencia</label>
                        <select name="asistencia" class="form-select" id="selectAsistencia" onchange="toggleMotivo(this.value)">
                            <option value="">Seleccionar...</option>
                            <option value="asistio">Sí asistió</option>
                            <option value="no_asistio">No asistió</option>
                        </select>
                    </div>
                    <div class="col-md-5" id="motivoWrap" style="display:none;">
                        <label class="form-label">Motivo de inasistencia</label>
                        <input type="text" name="motivo_inasistencia" class="form-control" placeholder="Opcional...">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">Registrar</button>
                    </div>
                </div>
            </form>
            <script>
                function toggleMotivo(val) {
                    document.getElementById('motivoWrap').style.display = val === 'no_asistio' ? 'block' : 'none';
                }
            </script>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>


<?php if($oficio->turno && $oficio->turno->asistencia !== 'pendiente'): ?>
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong>
            <span style="color:var(--mint);">⑥</span>
            <?php if($oficio->turno->asistencia === 'asistio'): ?>
                Informe clínico
            <?php else: ?>
                Informe de inasistencia
            <?php endif; ?>
        </strong>
        <?php if(!$oficio->informe): ?>
            <?php if($oficio->turno->asistencia === 'asistio'): ?>
                <a href="<?php echo e(route('informes.create', $oficio)); ?>" class="btn btn-sm btn-primary">+ Cargar informe clínico</a>
            <?php else: ?>
                <a href="<?php echo e(route('informes.createInasistencia', $oficio)); ?>" class="btn btn-sm btn-warning">+ Cargar informe de inasistencia</a>
            <?php endif; ?>
        <?php else: ?>
            <div class="d-flex gap-1">
                <a href="<?php echo e(route('informes.pdf', $oficio->informe)); ?>" class="btn btn-sm btn-success" target="_blank">PDF</a>
                <a href="<?php echo e(route('informes.edit', $oficio->informe)); ?>" class="btn btn-sm btn-warning">Editar</a>
                <form action="<?php echo e(route('informes.destroy', $oficio->informe)); ?>" method="POST"
                      onsubmit="return confirm('¿Eliminar este informe?')">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <?php if($oficio->informe): ?>
            <div class="row">
                <div class="col-md-4">
                    <strong>Tipo:</strong>
                    <span class="badge bg-<?php echo e($oficio->informe->tipo === 'clinico' ? 'info' : 'warning'); ?>">
                        <?php echo e($oficio->informe->tipo === 'clinico' ? 'Clínico' : 'Inasistencia'); ?>

                    </span>
                </div>
                <div class="col-md-4"><strong>Fecha:</strong> <?php echo e(\Carbon\Carbon::parse($oficio->informe->fecha_informe)->format('d/m/Y')); ?></div>
                <div class="col-md-4"><strong>Profesional:</strong> <?php echo e($oficio->informe->profesional->apellido); ?>, <?php echo e($oficio->informe->profesional->nombre); ?></div>
            </div>
        <?php else: ?>
            <p class="text-muted mb-0">Sin informe cargado.</p>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>


<?php if($oficio->informe): ?>
<div class="card mb-3">
    <div class="card-header">
        <strong><span style="color:var(--mint);">⑦</span> Envío del informe</strong>
    </div>
    <div class="card-body">
        <div class="row g-3">
            
            <div class="col-md-6">
                <div class="p-3 rounded" style="background:var(--surface2); border:1px solid var(--border);">
                    <strong>Juzgado</strong><br>
                    <?php if($oficio->informe->enviado_juzgado): ?>
                        <span class="badge bg-success mt-1">✓ Enviado el <?php echo e(\Carbon\Carbon::parse($oficio->informe->fecha_envio)->format('d/m/Y')); ?></span>
                    <?php else: ?>
                        <span class="badge bg-warning mt-1">Pendiente</span>
                        <form action="<?php echo e(route('informes.marcarEnviado', $oficio->informe)); ?>" method="POST" class="mt-2">
                            <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                            <button type="submit" class="btn btn-sm btn-success"
                                    onclick="return confirm('¿Confirmar envío al juzgado?')">
                                Marcar como enviado
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="p-3 rounded" style="background:var(--surface2); border:1px solid var(--border);">
                    <strong>Dirección</strong><br>
                    <?php if($oficio->informe->enviado_direccion): ?>
                        <span class="badge bg-success mt-1">✓ Enviado el <?php echo e(\Carbon\Carbon::parse($oficio->informe->fecha_envio_direccion)->format('d/m/Y')); ?></span>
                    <?php else: ?>
                        <span class="badge bg-warning mt-1">Pendiente</span>
                        <form action="<?php echo e(route('informes.marcarEnviadoDireccion', $oficio->informe)); ?>" method="POST" class="mt-2">
                            <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                            <button type="submit" class="btn btn-sm btn-success"
                                    onclick="return confirm('¿Confirmar envío a Dirección?')">
                                Marcar como enviado
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>


<?php if($oficio->informe && $oficio->informe->enviado_juzgado && $oficio->informe->enviado_direccion): ?>
<div class="card mb-3" style="border-left: 4px solid var(--green) !important;">
    <div class="card-body d-flex align-items-center gap-3">
        <div style="width:44px;height:44px;border-radius:50%;background:linear-gradient(135deg,var(--mint),var(--green));display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg width="22" height="22" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <div>
            <strong style="color:var(--green);">⑧ Cierre administrativo completado</strong>
            <p class="mb-0 text-muted" style="font-size:0.85rem;">El informe fue enviado al juzgado y a Dirección. El proceso está finalizado.</p>
        </div>
    </div>
</div>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\KINGS OF PC\Herd\sipsi\resources\views/oficios/show.blade.php ENDPATH**/ ?>