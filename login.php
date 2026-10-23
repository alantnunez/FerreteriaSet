<?php
declare(strict_types=1);
require_once __DIR__ . '/funciones.php';
if (usuarioActual()) { header('Location: menu.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombreUsuario = trim((string)($_POST['usuario'] ?? ''));
    $clave = (string)($_POST['password'] ?? '');
    try {
        $pdo = db();
        $q = $pdo->prepare('SELECT id_usuario, usuario, password_hash, id_rol FROM usuario WHERE usuario = ? AND activo = 1 LIMIT 1');
        $q->execute([$nombreUsuario]);
        $fila = $q->fetch();
        $ok = $fila && password_verify($clave, $fila['password_hash']);
        $log = $pdo->prepare('INSERT INTO auditoria_acceso (usuario, exito) VALUES (?, ?)');
        $log->execute([$nombreUsuario, $ok ? 1 : 0]);
        if ($ok) {
            session_regenerate_id(true);
            $_SESSION['id_usuario'] = (int)$fila['id_usuario'];
            $_SESSION['id_rol'] = (int)$fila['id_rol'];
            $_SESSION['usuario'] = $fila['usuario'];
            header('Location: menu.php'); exit;
        }
        $error = 'Usuario o contraseña incorrectos.';
    } catch (Throwable $e) {
        $error = 'No se pudo conectar o consultar la base de datos. Revisa config.php.';
    }
}
ob_start(); ?>
<?php if ($error): ?><p class="alerta"><?= e($error) ?></p><?php endif; ?>
<form method="post" class="formulario">
  <label>Usuario<input name="usuario" maxlength="30" required autocomplete="username"></label>
  <label>Contraseña<input type="password" name="password" required autocomplete="current-password"></label>
  <button type="submit">Entrar</button>
</form>
<p>¿No tienes cuenta? <a href="registro.php">Crear cuenta</a></p>
<?php pagina('Iniciar sesión', ob_get_clean()); ?>
