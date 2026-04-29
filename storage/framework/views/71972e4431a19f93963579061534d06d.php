<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body  { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #2c3e6b; margin: 30px; }
        h1    { font-size: 18px; font-weight: bold; margin-bottom: 4px; }
        p     { margin: 0 0 16px; color: #666; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        thead th {
            background: #f5c518; color: #333;
            padding: 8px 10px; text-align: left;
            font-size: 11px; text-transform: uppercase;
        }
        tbody td { padding: 7px 10px; border-bottom: 1px solid #eee; }
        tbody tr:nth-child(even) td { background: #f9f9f9; }
        tfoot td { background: #5cb85c; color: #fff; padding: 8px 10px; font-weight: bold; }
        .right       { text-align: right; }
        .chart       { margin-top: 28px; text-align: center; }
        .chart-title { font-size: 12px; font-weight: bold; color: #cc0000; text-transform: uppercase; margin-bottom: 10px; }
        .footer      { margin-top: 30px; font-size: 10px; color: #aaa; text-align: right; }
    </style>
</head>
<body>
    <h1>Estadísticas — Oficios Judiciales</h1>
    <p>Período: <?php echo e($periodo); ?></p>

    <table>
        <thead>
            <tr>
                <th>Oficina / Juzgado</th>
                <th class="right">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $datos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($row->nombre); ?></td>
                <td class="right"><?php echo e($row->total); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
        <tfoot>
            <tr>
                <td>TOTAL</td>
                <td class="right"><?php echo e($totalGeneral); ?></td>
            </tr>
        </tfoot>
    </table>

    <?php if(!empty($chartImg) && str_starts_with($chartImg, 'data:image/png;base64,')): ?>
    <div class="chart">
        <div class="chart-title">Oficios Judiciales — <?php echo e($periodo); ?></div>
        <img src="<?php echo e($chartImg); ?>" style="max-width:460px; height:auto;">
    </div>
    <?php endif; ?>

    <div class="footer">Generado el <?php echo e(now()->format('d/m/Y H:i')); ?> — SIPSI Psiquiatría Hospitalaria</div>
</body>
</html>
<?php /**PATH C:\Users\KINGS OF PC\Herd\sipsi\resources\views/juzgados/estadisticas-pdf.blade.php ENDPATH**/ ?>