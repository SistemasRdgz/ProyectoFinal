<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Tiempo máximo de inactividad: 15 minutos
$tiempoMaximoInactividad = 15 * 60;

if (isset($_SESSION['ultimo_acceso'])) {
    $tiempoInactivo = time() - $_SESSION['ultimo_acceso'];

    if ($tiempoInactivo > $tiempoMaximoInactividad) {
        session_unset();
        session_destroy();

        session_start();
        $_SESSION['error'] = "La sesión expiró por inactividad. Inicie sesión nuevamente.";
        header("Location: index.php");
        exit();
    }
}

$_SESSION['ultimo_acceso'] = time();

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

function usuarioActual()
{
    return $_SESSION['user'] ?? null;
}

function esAdmin()
{
    return isset($_SESSION['user']['Rol']) && strtolower($_SESSION['user']['Rol']) === 'admin';
}

function requiereAdmin()
{
    if (!esAdmin()) {
        $_SESSION['error'] = "No tiene permisos para acceder a esta sección.";
        header("Location: dashboard.php");
        exit();
    }
}