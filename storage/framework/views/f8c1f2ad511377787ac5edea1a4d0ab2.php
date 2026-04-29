<?php $__env->startSection('content'); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="page-header-title">Oficios</h2>
        <p class="page-header-sub">Listado de oficios judiciales</p>
    </div>
    <div class="d-flex gap-2">
        <button onclick="document.getElementById('modalImportarOficios').classList.add('active')" class="btn btn-secondary" style="display:flex;align-items:center;gap:0.4rem;">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
            </svg>
            Importar Excel
        </button>
        <a href="<?php echo e(route('oficios.create')); ?>" class="btn btn-primary">+ Nuevo oficio</a>
    </div>
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



<div class="confirm-overlay" id="modalImportarOficios">
    <div class="confirm-box" style="max-width:480px;text-align:left;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;">
            <div style="display:flex;align-items:center;gap:0.65rem;">
                <div class="confirm-icon" style="margin:0;background:rgba(139,167,217,0.12);">
                    <svg width="22" height="22" fill="none" stroke="var(--lavender)" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                </div>
                <div>
                    <div class="confirm-title" style="text-align:left;margin:0;">Importar oficios</div>
                    <div style="font-size:0.78rem;color:var(--text-muted);">Desde archivo Excel o CSV</div>
                </div>
            </div>
            <button onclick="document.getElementById('modalImportarOficios').classList.remove('active')"
                    style="background:none;border:none;cursor:pointer;color:var(--text-muted);font-size:1.2rem;line-height:1;">✕</button>
        </div>

        <div style="background:var(--surface2);border-radius:0.6rem;padding:0.85rem 1rem;margin-bottom:1rem;font-size:0.82rem;color:var(--text-soft);">
            <strong style="display:block;margin-bottom:0.4rem;color:var(--text);">Columnas del archivo:</strong>
            <div style="margin-top:0.4rem;display:flex;flex-wrap:wrap;gap:0.3rem;">
                <?php $__currentLoopData = ['numero_oficio','juzgado','dni_paciente','nombre_paciente','apellido_paciente','fecha_recepcion','medio_recepcion','tipo_pedido','observaciones']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $col): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span style="background:var(--surface);border:1px solid var(--border);border-radius:4px;padding:2px 7px;font-size:0.72rem;font-family:monospace;color:var(--lavender);"><?php echo e($col); ?></span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <div style="margin-top:0.6rem;color:var(--text-muted);font-size:0.78rem;">
                <strong>numero_oficio</strong>, <strong>juzgado</strong> y <strong>dni_paciente</strong> son obligatorios.
                Si el paciente o juzgado no existe, se crea automáticamente.
                Medio: <em>papel</em>, <em>email</em> o <em>whatsapp</em>.
            </div>
        </div>

        <div style="margin-bottom:1rem;">
            <a href="<?php echo e(route('oficios.plantilla')); ?>"
               style="font-size:0.8rem;color:var(--lavender);text-decoration:none;display:inline-flex;align-items:center;gap:0.3rem;">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17v3a1 1 0 001 1h16a1 1 0 001-1v-3"/>
                </svg>
                Descargar plantilla de ejemplo (.csv)
            </a>
        </div>

        <form action="<?php echo e(route('oficios.importar')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div style="margin-bottom:1rem;">
                <label style="font-size:0.82rem;font-weight:600;color:var(--text-soft);display:block;margin-bottom:0.4rem;">
                    Seleccioná el archivo
                </label>
                <input type="file" name="archivo" accept=".xlsx,.xls,.csv" class="form-control" required>
                <?php $__errorArgs = ['archivo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div style="color:#dc2626;font-size:0.78rem;margin-top:0.3rem;"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="confirm-actions" style="justify-content:flex-end;">
                <button type="button" class="btn btn-secondary"
                        onclick="document.getElementById('modalImportarOficios').classList.remove('active')">
                    Cancelar
                </button>
                <button type="submit" class="btn btn-primary">Importar</button>
            </div>
        </form>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\KINGS OF PC\Herd\sipsi\resources\views/oficios/index.blade.php ENDPATH**/ ?>