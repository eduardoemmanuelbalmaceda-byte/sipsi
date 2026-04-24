<?php $__env->startSection('content'); ?>
<h2 class="mb-4">Asignar turno — Oficio <?php echo e($oficio->numero_oficio); ?></h2>
<form action="<?php echo e(route('turnos.store')); ?>" method="POST">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="oficio_id" value="<?php echo e($oficio->id); ?>">
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Profesional</label>
            <select name="profesional_id" class="form-select <?php $__errorArgs = ['profesional_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                <option value="">Seleccionar profesional...</option>
                <?php $__currentLoopData = $profesionales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $profesional): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($profesional->id); ?>" <?php echo e(old('profesional_id') == $profesional->id ? 'selected' : ''); ?>>
                    <?php echo e($profesional->apellido); ?>, <?php echo e($profesional->nombre); ?> — <?php echo e($profesional->especialidad); ?>

                </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <?php $__errorArgs = ['profesional_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Fecha</label>
            <input type="date" name="fecha_turno" class="form-control <?php $__errorArgs = ['fecha_turno'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('fecha_turno')); ?>">
            <?php $__errorArgs = ['fecha_turno'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Hora</label>
            <input type="time" name="hora" class="form-control <?php $__errorArgs = ['hora'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('hora')); ?>">
            <?php $__errorArgs = ['hora'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    </div>
    <a href="<?php echo e(route('oficios.show', $oficio)); ?>" class="btn btn-secondary">Cancelar</a>
    <button type="submit" class="btn btn-primary">Guardar turno</button>
</form>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sipsi\resources\views/turnos/create.blade.php ENDPATH**/ ?>