<?php
class User {
    private $conn;
    private $table = "Usuarios";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function login($correo) {
        $query = "SELECT * FROM $this->table WHERE Correo = :correo LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":correo", $correo);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crear($nombre, $correo, $pass, $rol) {
        $query = "INSERT INTO $this->table (Nombre, Correo, Pass, Rol)
                  VALUES (:nombre, :correo, :pass, :rol)";
        $stmt = $this->conn->prepare($query);

        $passHash = password_hash($pass, PASSWORD_BCRYPT);

        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":correo", $correo);
        $stmt->bindParam(":pass", $passHash);
        $stmt->bindParam(":rol", $rol);

        return $stmt->execute();
    }
}