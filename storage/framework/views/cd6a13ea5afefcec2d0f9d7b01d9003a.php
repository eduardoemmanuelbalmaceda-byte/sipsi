<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2><?php echo e($paciente->apellido); ?>, <?php echo e($paciente->nombre); ?></h2>
    <a href="<?php echo e(route('pacientes.index')); ?>" class="btn btn-secondary">Volver</a>
</div>
<div class="card mb-4">
    <div class="card-body">
        <p><strong>DNI:</strong> <?php echo e($paciente->dni); ?></p>
        <p><strong>Fecha de nacimiento:</strong> <?php echo e($paciente->fecha_nacimiento ?? '-'); ?></p>
        <p><strong>Teléfono:</strong> <?php echo e($paciente->telefono ?? '-'); ?></p>
        <p><strong>Dirección:</strong> <?php echo e($paciente->direccion ?? '-'); ?></p>
    </div>
</div>
<h4>Oficios vinculados</h4>
<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>Número</th>
            <th>Juzgado</th>
            <th>Fecha</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $paciente->oficios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $oficio): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr>
            <td><?php echo e($oficio->numero_oficio); ?></td>
            <td><?php echo e($oficio->juzgado->nombre); ?></td>
            <td><?php echo e($oficio->fecha_recepcion); ?></td>
            <td><span class="badge bg-<?php echo e($oficio->estado == 'cerrado' ? 'success' : ($oficio->estado == 'en_curso' ? 'warning' : 'secondary')); ?>"><?php echo e($oficio->estado); ?></span></td>
            <td><a href="<?php echo e(route('oficios.show', $oficio)); ?>" class="btn btn-sm btn-info">Ver</a></td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr><td colspan="5">No tiene oficios registrados.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\KINGS OF PC\Herd\sipsi\resources\views/pacientes/show.blade.php ENDPATH**/ ?>