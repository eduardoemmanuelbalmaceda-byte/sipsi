<?php $__env->startSection('content'); ?>
<h2 class="mb-4">Editar oficio</h2>
<form action="<?php echo e(route('oficios.update', $oficio)); ?>" method="POST">
    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Número de oficio</label>
            <input type="text" name="numero_oficio" class="form-control <?php $__errorArgs = ['numero_oficio'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('numero_oficio', $oficio->numero_oficio)); ?>">
            <?php $__errorArgs = ['numero_oficio'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Fecha de recepción</label>
            <input type="date" name="fecha_recepcion" class="form-control" value="<?php echo e(old('fecha_recepcion', $oficio->fecha_recepcion)); ?>">
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Paciente</label>
            <select name="paciente_id" class="form-select">
                <?php $__currentLoopData = $pacientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paciente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($paciente->id); ?>" <?php echo e($oficio->paciente_id == $paciente->id ? 'selected' : ''); ?>>
                    <?php echo e($paciente->apellido); ?>, <?php echo e($paciente->nombre); ?>

                </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Juzgado</label>
            <select name="juzgado_id" class="form-select">
                <?php $__currentLoopData = $juzgados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $juzgado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($juzgado->id); ?>" <?php echo e($oficio->juzgado_id == $juzgado->id ? 'selected' : ''); ?>>
                    <?php echo e($juzgado->nombre); ?>

                </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">Medio de recepción</label>
            <select name="medio_recepcion" class="form-select">
                <option value="papel" <?php echo e($oficio->medio_recepcion == 'papel' ? 'selected' : ''); ?>>Papel</option>
                <option value="email" <?php echo e($oficio->medio_recepcion == 'email' ? 'selected' : ''); ?>>Email</option>
                <option value="whatsapp" <?php echo e($oficio->medio_recepcion == 'whatsapp' ? 'selected' : ''); ?>>WhatsApp</option>
            </select>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Tipo de pedido</label>
            <input type="text" name="tipo_pedido" class="form-control" value="<?php echo e(old('tipo_pedido', $oficio->tipo_pedido)); ?>">
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Estado</label>
            <select name="estado" class="form-select">
                <option value="pendiente" <?php echo e($oficio->estado == 'pendiente' ? 'selected' : ''); ?>>Pendiente</option>
                <option value="en_curso" <?php echo e($oficio->estado == 'en_curso' ? 'selected' : ''); ?>>En curso</option>
                <option value="cerrado" <?php echo e($oficio->estado == 'cerrado' ? 'selected' : ''); ?>>Cerrado</option>
            </select>
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Observaciones</label>
        <textarea name="observaciones" class="form-control" rows="3"><?php echo e(old('observaciones', $oficio->observaciones)); ?></textarea>
    </div>
    <a href="<?php echo e(route('oficios.index')); ?>" class="btn btn-secondary">Cancelar</a>
    <button type="submit" class="btn btn-primary">Actualizar</button>
</form>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\KINGS OF PC\Herd\sipsi\resources\views/oficios/edit.blade.php ENDPATH**/ ?>