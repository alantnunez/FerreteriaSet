<?php
declare(strict_types=1);
require_once __DIR__ . '/funciones.php';
exigirLogin();
$rol = (int)$_SESSION['id_rol'];
ob_start(); ?>
<p>Bienvenido, <strong><?= e((string)$_SESSION['usuario']) ?></strong>.</p>
<p>Tu rol es: <strong><?= e(nombreRol($rol)) ?></strong>.</p>
<nav class="opciones">
<?php if ($rol === 1): ?>
<a class="opcion" href="admin.php">Administración de usuarios y accesos</a>
<?php elseif ($rol === 2): ?>
<a class="opcion" href="propietario.php">Resumen del negocio</a>
<?php elseif ($rol === 3): ?>
<a class="opcion" href="empleado.php">Área de empleado</a>
<?php endif; ?>
<a class="opcion secundario" href="logout.php">Cerrar sesión</a>
</nav>
<?php if (isset($_GET['error'])): ?><p class="alerta">No tienes permiso para entrar a esa página.</p><?php endif; ?>
<?php pagina('Menú principal', ob_get_clean()); ?>
