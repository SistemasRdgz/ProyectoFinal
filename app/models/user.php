<?php
class User {
    private $conn;
    private $table = "Usuarios";

public function login($correo) {
    $query = "SELECT * FROM $this->table 
              WHERE Correo = :correo 
              LIMIT 1";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":correo", $correo);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $stmt = $this->conn->query("SELECT IdUsuario, Nombre, Correo, Rol, Estado FROM $this->table");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE IdUsuario=:id");
        $stmt->execute([":id"=>$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

public function crear($nombre, $correo, $pass, $rol) {
    $sql = "INSERT INTO $this->table (Nombre, Correo, Pass, Rol)
            VALUES (:n, :c, :p, :r)";
    
    $stmt = $this->conn->prepare($sql);
    $hash = password_hash($pass, PASSWORD_BCRYPT);

    return $stmt->execute([
        ":n"=>$nombre,
        ":c"=>$correo,
        ":p"=>$hash,
        ":r"=>$rol
    ]);
}

    public function actualizar($id, $nombre, $correo, $rol) {
        $sql = "UPDATE $this->table 
                SET Nombre=:n, Correo=:c, Rol=:r
                WHERE IdUsuario=:id";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ":id"=>$id,
            ":n"=>$nombre,
            ":c"=>$correo,
            ":r"=>$rol
        ]);
    }

    public function eliminar($id) {
        $stmt = $this->conn->prepare("DELETE FROM $this->table WHERE IdUsuario=:id");
        return $stmt->execute([":id"=>$id]);
    }

    public function toggleEstado($id, $estado) {
        $stmt = $this->conn->prepare("UPDATE $this->table SET Estado=:e WHERE IdUsuario=:id");
        return $stmt->execute([":e"=>$estado, ":id"=>$id]);
    }
}