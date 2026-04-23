<?php $__env->startSection('content'); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="page-header-title">Oficios</h2>
        <p class="page-header-sub">Listado de oficios judiciales</p>
    </div>
    <a href="<?php echo e(route('oficios.create')); ?>" class="btn btn-primary">+ Nuevo oficio</a>
</div>


<form method="GET" action="<?php echo e(route('oficios.index')); ?>" class="mb-3">
    <div class="search-bar">
        <div class="search-input-wrap">
            <svg class="search-icon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/>
            </svg>
            <input type="text" name="q" value="<?php echo e($q ?? ''); ?>"
                placeholder="Buscar por número, paciente, juzgado..."
                class="search-input" autocomplete="off" />
            <?php if($q): ?>
                <a href="<?php echo e(route('oficios.index')); ?>" class="search-clear">✕</a>
            <?php endif; ?>
        </div>

        <select name="estado" class="filter-select" onchange="this.form.submit()">
            <option value="">Todos los estados</option>
            <option value="pendiente"  <?php echo e(($estado ?? '') == 'pendiente'  ? 'selected' : ''); ?>>Pendiente</option>
            <option value="en_curso"   <?php echo e(($estado ?? '') == 'en_curso'   ? 'selected' : ''); ?>>En curso</option>
            <option value="cerrado"    <?php echo e(($estado ?? '') == 'cerrado'    ? 'selected' : ''); ?>>Cerrado</option>
        </select>

        <button type="submit" class="btn btn-primary btn-sm px-3">Buscar</button>

        <?php if($q || $estado): ?>
            <a href="<?php echo e(route('oficios.index')); ?>" class="btn btn-secondary btn-sm px-3">Limpiar</a>
        <?php endif; ?>
    </div>
</form>

<?php if($q || $estado): ?>
    <p class="search-results-info">
        <?php echo e($oficios->total()); ?> resultado(s)
        <?php if($q): ?> para "<strong><?php echo e($q); ?></strong>"<?php endif; ?>
        <?php if($estado): ?> · estado: <strong><?php echo e($estado); ?></strong><?php endif; ?>
    </p>
<?php endif; ?>

<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Número</th>
                <th>Paciente</th>
                <th>Juzgado</th>
                <th>Fecha</th>
                <th>Medio</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $oficios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $oficio): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><strong><?php echo e($oficio->numero_oficio); ?></strong></td>
                <td><?php echo e($oficio->paciente->apellido); ?>, <?php echo e($oficio->paciente->nombre); ?></td>
                <td><?php echo e($oficio->juzgado->nombre); ?></td>
                <td><?php echo e(\Carbon\Carbon::parse($oficio->fecha_recepcion)->format('d/m/Y')); ?></td>
                <td><?php echo e(ucfirst($oficio->medio_recepcion)); ?></td>
                <td>
                    <?php if($oficio->estado == 'cerrado'): ?>
                        <span class="badge bg-success">Cerrado</span>
                    <?php elseif($oficio->estado == 'en_curso'): ?>
                        <span class="badge bg-warning">En curso</span>
                    <?php else: ?>
                        <span class="badge bg-secondary">Pendiente</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="<?php echo e(route('oficios.show', $oficio)); ?>" class="btn btn-sm btn-info">Ver</a>
                    <a href="<?php echo e(route('oficios.edit', $oficio)); ?>" class="btn btn-sm btn-warning">Editar</a>
                    <form action="<?php echo e(route('oficios.destroy', $oficio)); ?>" method="POST" class="d-inline">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button type="button" onclick="confirmDelete(this.closest('form'))" class="btn btn-sm btn-danger">Eliminar</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="7" class="text-center py-4" style="color:var(--text-muted);">
                    <?php echo e($q || $estado ? 'No se encontraron resultados.' : 'No hay oficios registrados.'); ?>

                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php echo e($oficios->links()); ?>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sipsi\resources\views/oficios/index.blade.php ENDPATH**/ ?>