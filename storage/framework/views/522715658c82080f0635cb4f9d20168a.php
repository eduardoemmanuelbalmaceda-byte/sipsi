<?php $__env->startSection('content'); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="page-header-title">Profesionales</h2>
        <p class="page-header-sub">Equipo de salud mental</p>
    </div>
    <a href="<?php echo e(route('profesionales.create')); ?>" class="btn btn-primary">+ Nuevo profesional</a>
</div>

<form method="GET" action="<?php echo e(route('profesionales.index')); ?>" class="mb-3">
    <div class="search-bar">
        <div class="search-input-wrap">
            <svg class="search-icon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/>
            </svg>
            <input type="text" name="q" value="<?php echo e($q ?? ''); ?>"
                placeholder="Buscar por nombre, especialidad o rol..."
                class="search-input" autocomplete="off" />
            <?php if($q): ?>
                <a href="<?php echo e(route('profesionales.index')); ?>" class="search-clear">✕</a>
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary btn-sm px-3">Buscar</button>
        <?php if($q): ?>
            <a href="<?php echo e(route('profesionales.index')); ?>" class="btn btn-secondary btn-sm px-3">Limpiar</a>
        <?php endif; ?>
    </div>
</form>

<?php if($q): ?>
    <p class="search-results-info"><?php echo e($profesionales->total()); ?> resultado(s) para "<strong><?php echo e($q); ?></strong>"</p>
<?php endif; ?>

<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Apellido</th>
                <th>Nombre</th>
                <th>Especialidad</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $profesionales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $profesional): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><strong><?php echo e($profesional->apellido); ?></strong></td>
                <td><?php echo e($profesional->nombre); ?></td>
                <td><?php echo e($profesional->especialidad); ?></td>
                <td><span class="badge bg-info"><?php echo e(ucfirst($profesional->rol)); ?></span></td>
                <td>
                    <a href="<?php echo e(route('profesionales.edit', $profesional)); ?>" class="btn btn-sm btn-warning">Editar</a>
                    <form action="<?php echo e(route('profesionales.destroy', $profesional)); ?>" method="POST" class="d-inline">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button type="button" onclick="confirmDelete(this.closest('form'))" class="btn btn-sm btn-danger">Eliminar</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="5" class="text-center py-4" style="color:var(--text-muted);">
                    <?php echo e($q ? 'No se encontraron resultados.' : 'No hay profesionales registrados.'); ?>

                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php echo e($profesionales->links()); ?>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sipsi\resources\views/profesionales/index.blade.php ENDPATH**/ ?>