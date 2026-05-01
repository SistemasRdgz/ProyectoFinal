<?php

class Producto {
    private $conn;
    private $table = "productos";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $sql = "SELECT 
                    p.*,
                    c.Nombre AS Categoria,
                    pr.Nombre AS Proveedor
                FROM {$this->table} p
                LEFT JOIN categorias c ON p.IdCategoria = c.IdCategoria
                LEFT JOIN proveedores pr ON p.IdProveedor = pr.IdProveedor
                ORDER BY p.IdProducto DESC";

        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE IdProducto = :id 
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":id" => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crear($nombre, $descripcion, $precio, $fechaVencimiento, $stockActual, $stockMinimo, $idCategoria, $idProveedor) {
        $sql = "INSERT INTO {$this->table} 
                (Nombre, Descripcion, Precio, FechaVencimiento, StockActual, StockMinimo, IdCategoria, IdProveedor)
                VALUES 
                (:nombre, :descripcion, :precio, :fecha_vencimiento, :stock_actual, :stock_minimo, :id_categoria, :id_proveedor)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ":nombre" => $nombre,
            ":descripcion" => $descripcion,
            ":precio" => $precio,
            ":fecha_vencimiento" => $fechaVencimiento,
            ":stock_actual" => $stockActual,
            ":stock_minimo" => $stockMinimo,
            ":id_categoria" => $idCategoria ?: null,
            ":id_proveedor" => $idProveedor ?: null
        ]);
    }

    public function actualizar($id, $nombre, $descripcion, $precio, $fechaVencimiento, $stockActual, $stockMinimo, $idCategoria, $idProveedor) {
        $sql = "UPDATE {$this->table}
                SET 
                    Nombre = :nombre,
                    Descripcion = :descripcion,
                    Precio = :precio,
                    FechaVencimiento = :fecha_vencimiento,
                    StockActual = :stock_actual,
                    StockMinimo = :stock_minimo,
                    IdCategoria = :id_categoria,
                    IdProveedor = :id_proveedor
                WHERE IdProducto = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ":id" => $id,
            ":nombre" => $nombre,
            ":descripcion" => $descripcion,
            ":precio" => $precio,
            ":fecha_vencimiento" => $fechaVencimiento,
            ":stock_actual" => $stockActual,
            ":stock_minimo" => $stockMinimo,
            ":id_categoria" => $idCategoria ?: null,
            ":id_proveedor" => $idProveedor ?: null
        ]);
    }

    public function eliminar($id) {
        $sql = "DELETE FROM {$this->table} 
                WHERE IdProducto = :id";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([":id" => $id]);
    }

    public function productosStockBajo() {
        $sql = "SELECT * FROM {$this->table}
                WHERE StockActual <= StockMinimo
                ORDER BY StockActual ASC";

        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function productosPorVencer($dias = 30) {
        $sql = "SELECT * FROM {$this->table}
                WHERE FechaVencimiento BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL :dias DAY)
                ORDER BY FechaVencimiento ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":dias", (int)$dias, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}