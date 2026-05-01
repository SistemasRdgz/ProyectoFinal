<?php
require_once "../helpers/auth.php";
require_once "../config/database.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = "Acceso no permitido.";
    header("Location: carrito.php");
    exit;
}

if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    $_SESSION['error'] = "No hay productos en el carrito.";
    header("Location: carrito.php");
    exit;
}

$db = (new Database())->connect();

$idUsuario = $_SESSION['user']['IdUsuario'] ?? null;
$carrito = $_SESSION['carrito'];

if (!$idUsuario) {
    $_SESSION['error'] = "No se pudo identificar el usuario de la sesión.";
    header("Location: carrito.php");
    exit;
}

try {
    $db->beginTransaction();

    $total = 0;
    $productosValidados = [];
    $idProveedorCompra = null;

    /* ===================== */
    /* 1. VALIDAR PRODUCTOS Y STOCK */
    /* ===================== */
    foreach ($carrito as $item) {

        $idProducto = filter_var($item['id'] ?? null, FILTER_VALIDATE_INT);
        $cantidad = filter_var($item['cantidad'] ?? null, FILTER_VALIDATE_INT);

        if (!$idProducto || !$cantidad || $cantidad <= 0) {
            throw new Exception("El carrito contiene productos inválidos.");
        }

        $sql = "SELECT IdProducto, Nombre, Precio, StockActual, IdProveedor 
                FROM productos 
                WHERE IdProducto = ? 
                LIMIT 1";

        $stmt = $db->prepare($sql);
        $stmt->execute([$idProducto]);
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$producto) {
            throw new Exception("Uno de los productos del carrito ya no existe.");
        }

        if ((int)$producto['StockActual'] < $cantidad) {
            throw new Exception("No hay stock suficiente para el producto: " . $producto['Nombre']);
        }

        if ($idProveedorCompra === null && !empty($producto['IdProveedor'])) {
            $idProveedorCompra = $producto['IdProveedor'];
        }

        $precio = (float)$producto['Precio'];
        $subtotal = $precio * $cantidad;
        $total += $subtotal;

        $productosValidados[] = [
            'idProducto' => $idProducto,
            'nombre' => $producto['Nombre'],
            'precio' => $precio,
            'cantidad' => $cantidad,
            'subtotal' => $subtotal
        ];
    }

    if ($idProveedorCompra === null) {
        $idProveedorCompra = 1;
    }

    /* ===================== */
    /* 2. CREAR COMPRA / TRANSACCIÓN */
    /* ===================== */
    $sql = "INSERT INTO compras (IdProveedor, Fecha, Total)
            VALUES (?, NOW(), ?)";

    $stmt = $db->prepare($sql);
    $stmt->execute([$idProveedorCompra, $total]);

    $idCompra = $db->lastInsertId();

    /* ===================== */
    /* 3. GUARDAR DETALLE, ACTUALIZAR STOCK Y REGISTRAR MOVIMIENTO */
    /* ===================== */
    foreach ($productosValidados as $item) {

        $sql = "INSERT INTO detallecompras
                (IdCompra, IdProducto, Cantidad, Precio)
                VALUES (?, ?, ?, ?)";

        $stmt = $db->prepare($sql);
        $stmt->execute([
            $idCompra,
            $item['idProducto'],
            $item['cantidad'],
            $item['precio']
        ]);

        $sql = "UPDATE productos
                SET StockActual = StockActual - ?
                WHERE IdProducto = ?";

        $stmt = $db->prepare($sql);
        $stmt->execute([
            $item['cantidad'],
            $item['idProducto']
        ]);

        $sql = "INSERT INTO movimientosinventario
                (IdProducto, IdUsuario, TipoMovimiento, Cantidad, Fecha)
                VALUES (?, ?, ?, ?, NOW())";

        $stmt = $db->prepare($sql);
        $stmt->execute([
            $item['idProducto'],
            $idUsuario,
            'SALIDA',
            $item['cantidad']
        ]);
    }

    /* ===================== */
    /* 4. CONFIRMAR TRANSACCIÓN */
    /* ===================== */
    $db->commit();

    unset($_SESSION['carrito']);

    $_SESSION['success'] = "Compra confirmada correctamente. El stock fue actualizado y el movimiento quedó registrado.";

    header("Location: carrito.php");
    exit;

} catch (Exception $e) {
    $db->rollBack();

    $_SESSION['error'] = $e->getMessage();

    header("Location: carrito.php");
    exit;
}