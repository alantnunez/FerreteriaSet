<?php
declare(strict_types=1);
require_once __DIR__ . '/config.php';
try {
    $pdo = db();
    // Asegurar nombres de roles usados por la aplicación.
    $roles = [1 => 'admin', 2 => 'propietario', 3 => 'empleado'];
    foreach ($roles as $id => $nombre) {
        $check = $pdo->prepare('SELECT id_rol FROM rol WHERE id_rol = ?');
        $check->execute([$id]);
        if ($check->fetch()) {
            $upd = $pdo->prepare('UPDATE rol SET nombre = ? WHERE id_rol = ?');
            $upd->execute([$nombre, $id]);
        } else {
            $ins = $pdo->prepare('INSERT INTO rol (id_rol, nombre) VALUES (?, ?)');
            $ins->execute([$id, $nombre]);
        }
    }
    $check = $pdo->prepare('SELECT id_usuario FROM usuario WHERE usuario = ?');
    $check->execute(['AlanT']);
    if ($check->fetch()) {
        echo '<h2>El usuario AlanT ya existe.</h2><p>No se cambió su contraseña. Puedes ir al <a href="login.php">login</a>.</p>';
        exit;
    }
    $hash = password_hash('alantnunez', PASSWORD_DEFAULT);
    $ins = $pdo->prepare('INSERT INTO usuario (nombre, usuario, password_hash, id_rol, activo) VALUES (?, ?, ?, ?, 1)');
    $ins->execute(['AlanT', 'AlanT', $hash, 1]);
    echo '<h2>Configuración completada</h2><p>Se creó el usuario administrador AlanT con una contraseña protegida.</p><p><strong>Importante:</strong> elimina el archivo setup.php de esta carpeta ahora.</p><p><a href="login.php">Ir al login</a></p>';
} catch (Throwable $e) {
    http_response_code(500);
    echo '<h2>No se pudo completar la configuración</h2><p>Revisa config.php, que XAMPP esté iniciado y que las tablas tengan las columnas del profesor.</p><pre>' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</pre>';
}
