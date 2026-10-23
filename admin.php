<?php
declare(strict_types=1);
require_once __DIR__ . '/funciones.php';
exigirRol([1]);
$pdo = db(); $mensaje = ''; $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim((string)($_POST['nombre'] ?? ''));
    $usuario = trim((string)($_POST['usuario'] ?? ''));
    $clave = (string)($_POST['password'] ?? '');
    $rol = (int)($_POST['id_rol'] ?? 3);
    if ($nombre === '' || $usuario === '' || strlen($clave) < 8 || !in_array($rol, [1,2,3], true)) {
        $error = 'Revisa los campos. La contraseña debe tener al menos 8 caracteres.';
    } else {
        try {
            $hash = password_hash($clave, PASSWORD_DEFAULT);
            $ins = $pdo->prepare('INSERT INTO usuario (nombre, usuario, password_hash, id_rol, activo) VALUES (?, ?, ?, ?, 1)');
            $ins->execute([$nombre, $usuario, $hash, $rol]);
            $mensaje = 'Usuario creado correctamente.';
        } catch (PDOException $e) { $error = 'No se pudo crear. Puede que el nombre de usuario ya exista.'; }
    }
}
$usuarios = $pdo->query('SELECT u.id_usuario, u.nombre, u.usuario, u.id_rol, u.activo, r.nombre AS rol FROM usuario u JOIN rol r ON u.id_rol = r.id_rol ORDER BY u.id_usuario')->fetchAll();
$auditoria = $pdo->query('SELECT usuario, exito, fecha FROM auditoria_acceso ORDER BY id_auditoria DESC LIMIT 20')->fetchAll();
ob_start(); ?>
<p><a href="menu.php">← Volver al menú</a></p>
<?php if ($mensaje): ?><p class="exito"><?= e($mensaje) ?></p><?php endif; ?>
<?php if ($error): ?><p class="alerta"><?= e($error) ?></p><?php endif; ?>
<h2>Crear usuario</h2>
<form method="post" class="formulario">
<label>Nombre completo<input name="nombre" maxlength="80" required></label>
<label>Usuario<input name="usuario" maxlength="30" required></label>
<label>Contraseña (mínimo 8 caracteres)<input type="password" name="password" minlength="8" required></label>
<label>Rol<select name="id_rol"><option value="3">empleado</option><option value="2">propietario</option><option value="1">admin</option></select></label>
<button type="submit">Crear usuario</button>
</form>
<h2>Usuarios registrados</h2>
<div class="tabla"><table><tr><th>ID</th><th>Nombre</th><th>Usuario</th><th>Rol</th><th>Activo</th></tr>
<?php foreach ($usuarios as $u): ?><tr><td><?= (int)$u['id_usuario'] ?></td><td><?= e($u['nombre']) ?></td><td><?= e($u['usuario']) ?></td><td><?= e($u['rol']) ?></td><td><?= $u['activo'] ? 'Sí' : 'No' ?></td></tr><?php endforeach; ?>
</table></div>
<h2>Últimos intentos de acceso</h2>
<div class="tabla"><table><tr><th>Usuario escrito</th><th>Resultado</th><th>Fecha</th></tr>
<?php foreach ($auditoria as $a): ?><tr><td><?= e($a['usuario']) ?></td><td><?= $a['exito'] ? 'Correcto' : 'Fallido' ?></td><td><?= e($a['fecha']) ?></td></tr><?php endforeach; ?>
</table></div>
<?php pagina('Administración', ob_get_clean()); ?>
