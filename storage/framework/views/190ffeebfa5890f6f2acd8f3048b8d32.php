<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Oficio <?php echo e($oficio->numero_oficio); ?></h2>
    <div>
        <a href="<?php echo e(route('oficios.edit', $oficio)); ?>" class="btn btn-warning">Editar</a>
        <a href="<?php echo e(route('oficios.index')); ?>" class="btn btn-secondary">Volver</a>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header"><strong>Datos del oficio</strong></div>
            <div class="card-body">
                <p><strong>Número:</strong> <?php echo e($oficio->numero_oficio); ?></p>
                <p><strong>Juzgado:</strong> <?php echo e($oficio->juzgado->nombre); ?></p>
                <p><strong>Fecha de recepción:</strong> <?php echo e($oficio->fecha_recepcion); ?></p>
                <p><strong>Medio:</strong> <?php echo e(ucfirst($oficio->medio_recepcion)); ?></p>
                <p><strong>Tipo de pedido:</strong> <?php echo e($oficio->tipo_pedido); ?></p>
                <p><strong>Estado:</strong>
                    <span class="badge bg-<?php echo e($oficio->estado == 'cerrado' ? 'success' : ($oficio->estado == 'en_curso' ? 'warning' : 'secondary')); ?>">
                        <?php echo e(ucfirst($oficio->estado)); ?>

                    </span>
                </p>
                <p><strong>Observaciones:</strong> <?php echo e($oficio->observaciones ?? '-'); ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header"><strong>Paciente</strong></div>
            <div class="card-body">
                <p><strong>Nombre:</strong> <?php echo e($oficio->paciente->apellido); ?>, <?php echo e($oficio->paciente->nombre); ?></p>
                <p><strong>DNI:</strong> <?php echo e($oficio->paciente->dni); ?></p>
                <p><strong>Teléfono:</strong> <?php echo e($oficio->paciente->telefono ?? '-'); ?></p>
                <a href="<?php echo e(route('pacientes.show', $oficio->paciente)); ?>" class="btn btn-sm btn-info">Ver historial</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>Turno</strong>
                <?php if(!$oficio->turno): ?>
                    <a href="<?php echo e(route('turnos.create', $oficio)); ?>" class="btn btn-sm btn-primary">+ Asignar turno</a>
                <?php else: ?>
                    <div class="d-flex gap-1">
                        <a href="<?php echo e(route('turnos.edit', $oficio->turno)); ?>" class="btn btn-sm btn-warning">Editar</a>
                        <form action="<?php echo e(route('turnos.destroy', $oficio->turno)); ?>" method="POST"
                              onsubmit="return confirm('¿Eliminar este turno? El oficio volverá a estado Pendiente.')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <?php if($oficio->turno): ?>
                    <p><strong>Fecha:</strong> <?php echo e($oficio->turno->fecha_turno); ?></p>
                    <p><strong>Hora:</strong> <?php echo e($oficio->turno->hora); ?></p>
                    <p><strong>Profesional:</strong> <?php echo e($oficio->turno->profesional->apellido); ?>, <?php echo e($oficio->turno->profesional->nombre); ?></p>
                    <p><strong>Estado:</strong> <?php echo e(ucfirst($oficio->turno->estado)); ?></p>
                <?php else: ?>
                    <p class="text-muted">Sin turno asignado.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>Informe</strong>
                <?php if(!$oficio->informe && $oficio->turno): ?>
                    <a href="<?php echo e(route('informes.create', $oficio)); ?>" class="btn btn-sm btn-primary">+ Cargar informe</a>
                <?php elseif($oficio->informe): ?>
                    <div class="d-flex gap-1">
                        <a href="<?php echo e(route('informes.pdf', $oficio->informe)); ?>" class="btn btn-sm btn-success" target="_blank">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:inline;vertical-align:middle;margin-right:2px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            PDF
                        </a>
                        <a href="<?php echo e(route('informes.edit', $oficio->informe)); ?>" class="btn btn-sm btn-warning">Editar</a>
                        <form action="<?php echo e(route('informes.destroy', $oficio->informe)); ?>" method="POST"
                              onsubmit="return confirm('¿Eliminar este informe? El oficio volverá a estado En curso.')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <?php if($oficio->informe): ?>
                    <p><strong>Fecha:</strong> <?php echo e($oficio->informe->fecha_informe); ?></p>
                    <p><strong>Profesional:</strong> <?php echo e($oficio->informe->profesional->apellido); ?>, <?php echo e($oficio->informe->profesional->nombre); ?></p>

                    <?php if($oficio->informe->enviado_juzgado): ?>
                        <p>
                            <strong>Envío al juzgado:</strong>
                            <span class="badge bg-success">
                                ✓ Enviado el <?php echo e(\Carbon\Carbon::parse($oficio->informe->fecha_envio)->format('d/m/Y')); ?>

                            </span>
                        </p>
                    <?php else: ?>
                        <p>
                            <strong>Envío al juzgado:</strong>
                            <span class="badge bg-warning">Pendiente</span>
                        </p>
                        <form action="<?php echo e(route('informes.marcarEnviado', $oficio->informe)); ?>" method="POST" class="mt-2">
                            <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                            <button type="submit" class="btn btn-sm btn-success"
                                    onclick="return confirm('¿Confirmar que el informe fue enviado al juzgado hoy?')">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="display:inline;vertical-align:middle;margin-right:3px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                Marcar como enviado
                            </button>
                        </form>
                    <?php endif; ?>
                <?php elseif(!$oficio->turno): ?>
                    <p class="text-muted">Primero asigná un turno.</p>
                <?php else: ?>
                    <p class="text-muted">Sin informe cargado.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\KINGS OF PC\Herd\sipsi\resources\views/oficios/show.blade.php ENDPATH**/ ?>