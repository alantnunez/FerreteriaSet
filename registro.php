<?php
declare(strict_types=1);
require_once __DIR__ . '/funciones.php';
$error = ''; $ok = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim((string)($_POST['nombre'] ?? ''));
    $usuario = trim((string)($_POST['usuario'] ?? ''));
    $clave = (string)($_POST['password'] ?? '');
    if ($nombre === '' || $usuario === '' || strlen($clave) < 8) {
        $error = 'Completa los campos. La contraseña debe tener al menos 8 caracteres.';
    } else {
        try {
            $pdo = db();
            $check = $pdo->prepare('SELECT id_usuario FROM usuario WHERE usuario = ?');
            $check->execute([$usuario]);
            if ($check->fetch()) {
                $error = 'Ese nombre de usuario ya existe.';
            } else {
                $hash = password_hash($clave, PASSWORD_DEFAULT);
                // El registro público siempre asigna el rol empleado (3).
                $insert = $pdo->prepare('INSERT INTO usuario (nombre, usuario, password_hash, id_rol, activo) VALUES (?, ?, ?, 3, 1)');
                $insert->execute([$nombre, $usuario, $hash]);
                $ok = 'Cuenta creada. Ya puedes iniciar sesión.';
            }
        } catch (Throwable $e) { $error = 'No se pudo crear la cuenta. Comprueba la conexión a la base de datos.'; }
    }
}
ob_start(); ?>
<?php if ($error): ?><p class="alerta"><?= e($error) ?></p><?php endif; ?>
<?php if ($ok): ?><p class="exito"><?= e($ok) ?></p><?php endif; ?>
<form method="post" class="formulario">
<label>Nombre completo<input name="nombre" maxlength="80" required></label>
<label>Nombre de usuario<input name="usuario" maxlength="30" required autocomplete="username"></label>
<label>Contraseña (mínimo 8 caracteres)<input type="password" name="password" minlength="8" required autocomplete="new-password"></label>
<button type="submit">Crear cuenta</button>
</form>
<p><a href="login.php">Volver al inicio de sesión</a></p>
<?php pagina('Registro de usuario', ob_get_clean()); ?>
