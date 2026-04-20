<?php $__env->startSection('content'); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="page-header-title">Pacientes</h2>
        <p class="page-header-sub">Listado de pacientes registrados</p>
    </div>
    <a href="<?php echo e(route('pacientes.create')); ?>" class="btn btn-primary">+ Nuevo paciente</a>
</div>

<form method="GET" action="<?php echo e(route('pacientes.index')); ?>" class="mb-3">
    <div class="search-bar">
        <div class="search-input-wrap">
            <svg class="search-icon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/>
            </svg>
            <input type="text" name="q" value="<?php echo e($q ?? ''); ?>"
                placeholder="Buscar por nombre, apellido o DNI..."
                class="search-input" autocomplete="off" />
            <?php if($q): ?>
                <a href="<?php echo e(route('pacientes.index')); ?>" class="search-clear">✕</a>
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary btn-sm px-3">Buscar</button>
        <?php if($q): ?>
            <a href="<?php echo e(route('pacientes.index')); ?>" class="btn btn-secondary btn-sm px-3">Limpiar</a>
        <?php endif; ?>
    </div>
</form>

<?php if($q): ?>
    <p class="search-results-info"><?php echo e($pacientes->total()); ?> resultado(s) para "<strong><?php echo e($q); ?></strong>"</p>
<?php endif; ?>

<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Apellido</th>
                <th>Nombre</th>
                <th>DNI</th>
                <th>Teléfono</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $pacientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paciente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><strong><?php echo e($paciente->apellido); ?></strong></td>
                <td><?php echo e($paciente->nombre); ?></td>
                <td><?php echo e($paciente->dni); ?></td>
                <td><?php echo e($paciente->telefono ?? '—'); ?></td>
                <td>
                    <a href="<?php echo e(route('pacientes.show', $paciente)); ?>" class="btn btn-sm btn-info">Ver</a>
                    <a href="<?php echo e(route('pacientes.edit', $paciente)); ?>" class="btn btn-sm btn-warning">Editar</a>
                    <form action="<?php echo e(route('pacientes.destroy', $paciente)); ?>" method="POST" class="d-inline">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button type="button" onclick="confirmDelete(this.closest('form'))" class="btn btn-sm btn-danger">Eliminar</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="5" class="text-center py-4" style="color:var(--text-muted);">
                    <?php echo e($q ? 'No se encontraron resultados.' : 'No hay pacientes registrados.'); ?>

                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php echo e($pacientes->links()); ?>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sipsi\resources\views/pacientes/index.blade.php ENDPATH**/ ?>