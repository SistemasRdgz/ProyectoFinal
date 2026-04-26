<?php
class Compra {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function crearCompra($idProveedor, $total, $detalles) {
        try {
            $this->conn->beginTransaction();

            // Insertar compra
            $query = "INSERT INTO Compras (IdProveedor, Fecha, Total)
                      VALUES (:proveedor, NOW(), :total)";
            $stmt = $this->conn->prepare($query);

            $stmt->execute([
                ":proveedor" => $idProveedor,
                ":total" => $total
            ]);

            $idCompra = $this->conn->lastInsertId();

            // Insertar detalle
            foreach ($detalles as $d) {
                $query = "INSERT INTO DetalleCompras (IdCompra, IdProducto, Cantidad, Precio)
                          VALUES (:compra, :producto, :cantidad, :precio)";
                $stmt = $this->conn->prepare($query);

                $stmt->execute([
                    ":compra" => $idCompra,
                    ":producto" => $d['id'],
                    ":cantidad" => $d['cantidad'],
                    ":precio" => $d['precio']
                ]);
            }

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }
}