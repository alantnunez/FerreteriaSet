<?php
declare(strict_types=1);
require_once __DIR__ . '/funciones.php';
exigirRol([3]);
ob_start(); ?>
<p><a href="menu.php">← Volver al menú</a></p>
<h2>Área de trabajo</h2>
<p>Has iniciado sesión como empleado. Esta pantalla es una demostración del área de trabajo.</p>
<p>Para registrar ventas reales, el grupo debe crear las tablas <code>producto</code>, <code>venta</code> y <code>detalle_venta</code> y conectar aquí el formulario.</p>
<?php pagina('Área de empleado', ob_get_clean()); ?>
