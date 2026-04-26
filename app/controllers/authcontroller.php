<?php
session_start();

require_once "../config/database.php";
require_once "../app/models/User.php";

$db = (new Database())->connect();
$user = new User($db);

if ($_POST) {
    $correo = $_POST['correo'];
    $pass = $_POST['password'];

    $data = $user->login($correo);

    if ($data && password_verify($pass, $data['Pass'])) {
        $_SESSION['user'] = $data;

        if ($data['Rol'] == 'admin') {
            header("Location: dashboard.php");
        } else {
            header("Location: home.php");
        }
    } else {
        echo "Credenciales incorrectas";
    }
}