<?php
declare(strict_types=1);
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
require_once __DIR__ . '/config.php';

function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
function usuarioActual(): ?array {
    return isset($_SESSION['id_usuario'], $_SESSION['id_rol'], $_SESSION['usuario'])
        ? ['id_usuario' => (int)$_SESSION['id_usuario'], 'id_rol' => (int)$_SESSION['id_rol'], 'usuario' => (string)$_SESSION['usuario']]
        : null;
}
function exigirLogin(): void {
    if (!usuarioActual()) {
        header('Location: login.php');
        exit;
    }
}
function exigirRol(array $roles): void {
    exigirLogin();
    if (!in_array((int)$_SESSION['id_rol'], $roles, true)) {
        header('Location: menu.php?error=permiso');
        exit;
    }
}
function nombreRol(int $id): string {
    return match ($id) {
        1 => 'admin',
        2 => 'propietario',
        3 => 'empleado',
        default => 'desconocido'
    };
}
function pagina(string $titulo, string $contenido): void {
    echo '<!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>' . e($titulo) . '</title><link rel="stylesheet" href="estilos.css"></head><body><main class="contenedor"><header><h1>' . e($titulo) . '</h1></header>' . $contenido . '<footer>Sistema de práctica · PHP y MariaDB</footer></main></body></html>';
}
