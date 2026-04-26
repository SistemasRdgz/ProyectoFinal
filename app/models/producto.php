<?php
class Producto {
    private $conn;
    private $table = "Productos";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $stmt = $this->conn->query("SELECT * FROM $this->table");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crear($nombre, $precio, $stock) {
        $query = "INSERT INTO $this->table (Nombre, Precio, StockActual)
                  VALUES (:nombre, :precio, :stock)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":precio", $precio);
        $stmt->bindParam(":stock", $stock);

        return $stmt->execute();
    }

    public function actualizar($id, $nombre, $precio) {
        $query = "UPDATE $this->table 
                  SET Nombre=:nombre, Precio=:precio 
                  WHERE IdProducto=:id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":precio", $precio);

        return $stmt->execute();
    }

    public function eliminar($id) {
        $stmt = $this->conn->prepare("DELETE FROM $this->table WHERE IdProducto=:id");
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }
}