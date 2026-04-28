<?php
session_start();

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../models/user.php";

$db = (new Database())->connect();
$user = new User($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $correo = trim($_POST['correo'] ?? '');
    $pass = trim($_POST['password'] ?? '');

    if ($correo === '' || $pass === '') {
        $_SESSION['error'] = "Debe ingresar correo y contraseña.";
        header("Location: ../../public/index.php");
        exit;
    }

    $data = $user->login($correo);

    if ($data && password_verify($pass, $data['Pass'])) {

        session_regenerate_id(true);

        $_SESSION['user'] = [
            'IdUsuario' => $data['IdUsuario'],
            'Nombre' => $data['Nombre'],
            'Correo' => $data['Correo'],
            'Rol' => $data['Rol']
        ];

        header("Location: ../../public/dashboard.php");
        exit;

    } else {
        $_SESSION['error'] = "Credenciales incorrectas. Verifique su correo y contraseña.";
        header("Location: ../../public/index.php");
        exit;
    }

} else {
    header("Location: ../../public/index.php");
    exit;
}