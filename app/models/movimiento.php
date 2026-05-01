<?php

class Movimiento {
    private $conn;
    private $table = "movimientosinventario";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $sql = "SELECT 
                    m.IdMovimiento,
                    m.IdProducto,
                    p.Nombre AS Producto,
                    m.IdUsuario,
                    u.Nombre AS Usuario,
                    m.TipoMovimiento,
                    m.Cantidad,
                    m.Fecha
                FROM {$this->table} m
                INNER JOIN productos p ON m.IdProducto = p.IdProducto
                INNER JOIN usuarios u ON m.IdUsuario = u.IdUsuario
                ORDER BY m.Fecha DESC";

        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registrar($idProducto, $idUsuario, $tipo, $cantidad) {
        $sql = "INSERT INTO {$this->table} 
                (IdProducto, IdUsuario, TipoMovimiento, Cantidad, Fecha)
                VALUES (:producto, :usuario, :tipo, :cantidad, NOW())";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ":producto" => $idProducto,
            ":usuario" => $idUsuario,
            ":tipo" => $tipo,
            ":cantidad" => $cantidad
        ]);
    }

    public function registrarConStock($idProducto, $idUsuario, $tipo, $cantidad) {
        try {
            $this->conn->beginTransaction();

            $sql = "SELECT StockActual FROM Productos 
                    WHERE IdProducto = :id 
                    LIMIT 1";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([":id" => $idProducto]);
            $producto = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$producto) {
                throw new Exception("El producto seleccionado no existe.");
            }

            $stockActual = (int)$producto['StockActual'];
            $cantidad = (int)$cantidad;

            if ($cantidad <= 0) {
                throw new Exception("La cantidad debe ser mayor que cero.");
            }

            if ($tipo === "ENTRADA") {
                $nuevoStock = $stockActual + $cantidad;
            } elseif ($tipo === "SALIDA") {
                if ($cantidad > $stockActual) {
                    throw new Exception("No hay stock suficiente para registrar la salida.");
                }

                $nuevoStock = $stockActual - $cantidad;
            } else {
                throw new Exception("El tipo de movimiento no es válido.");
            }

            $sql = "UPDATE Productos 
                    SET StockActual = :stock 
                    WHERE IdProducto = :id";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ":stock" => $nuevoStock,
                ":id" => $idProducto
            ]);

            $sql = "INSERT INTO {$this->table} 
                    (IdProducto, IdUsuario, TipoMovimiento, Cantidad, Fecha)
                    VALUES (:producto, :usuario, :tipo, :cantidad, NOW())";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ":producto" => $idProducto,
                ":usuario" => $idUsuario,
                ":tipo" => $tipo,
                ":cantidad" => $cantidad
            ]);

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }
}