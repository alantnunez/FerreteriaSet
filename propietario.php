<?php
declare(strict_types=1);
require_once __DIR__ . '/funciones.php';
exigirRol([2]);
$pdo = db();
$total = (int)$pdo->query('SELECT COUNT(*) FROM usuario WHERE activo = 1')->fetchColumn();
$intentos = (int)$pdo->query('SELECT COUNT(*) FROM auditoria_acceso')->fetchColumn();
$fallidos = (int)$pdo->query('SELECT COUNT(*) FROM auditoria_acceso WHERE exito = 0')->fetchColumn();
ob_start(); ?>
<p><a href="menu.php">← Volver al menú</a></p>
<p>Esta pantalla muestra un resumen básico de uso del sistema.</p>
<div class="resumen"><div><span>Usuarios activos</span><strong><?= $total ?></strong></div><div><span>Intentos de acceso</span><strong><?= $intentos ?></strong></div><div><span>Intentos fallidos</span><strong><?= $fallidos ?></strong></div></div>
<p>Este resumen usa las tablas de usuarios y auditoría. El informe de ventas debe conectarse a las tablas de negocio del grupo.</p>
<?php pagina('Resumen del propietario', ob_get_clean()); ?>
