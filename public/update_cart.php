<?php
session_start();

// Obtenemos la acción (add, sub, remove) y el ID del producto desde la URL  ,
$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? null;

// Verificamos que tengamos un ID y que el carrito exista en la sesión
if ($id && isset($_SESSION['carrito'])) {
    
    // Recorremos el carrito para buscar el producto
    foreach ($_SESSION['carrito'] as $key => &$item) {
        if ($item['id'] == $id) {
            
            // Si la acción es sumar
            if ($action === 'add') {
                $item['cantidad']++;
            } 
            // Si la acción es restar
            elseif ($action === 'sub') {
                $item['cantidad']--;
                // Si la cantidad llega a 0, lo eliminamos del carrito
                if ($item['cantidad'] <= 0) {
                    unset($_SESSION['carrito'][$key]);
                }
            } 
            // Si la acción es eliminar por completo
            elseif ($action === 'remove') {
                unset($_SESSION['carrito'][$key]);
            }
            
            break; // Salimos del ciclo porque ya encontramos y modificamos el producto
        }
    }
    
    // Reindexamos el arreglo para evitar huecos en los índices
    $_SESSION['carrito'] = array_values($_SESSION['carrito']);
}

// Redirigimos de vuelta al carrito
header('Location: carrito.php');
exit;