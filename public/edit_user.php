<?php
require_once "../config/database.php";
require_once "../app/models/User.php";

$db = (new Database())->connect();
$model = new User($db);

$user = $model->getById($_GET['id']);
?>

<form action="update_user.php" method="POST">
    <input type="hidden" name="id" value="<?= $user['IdUsuario'] ?>">
    <input name="nombre" value="<?= $user['Nombre'] ?>">
    <input name="correo" value="<?= $user['Correo'] ?>">
    <button>Actualizar</button>
</form>