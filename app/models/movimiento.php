<?php
class Movimiento {
    private $conn;
    private $table = "MovimientosInventario";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function registrar($idProducto, $idUsuario, $tipo, $cantidad) {
        $query = "INSERT INTO $this->table 
        (IdProducto, IdUsuario, TipoMovimiento, Cantidad, Fecha)
        VALUES (:producto, :usuario, :tipo, :cantidad, NOW())";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ":producto" => $idProducto,
            ":usuario" => $idUsuario,
            ":tipo" => $tipo,
            ":cantidad" => $cantidad
        ]);
    }
}