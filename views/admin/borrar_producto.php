<?php
include_once '../../config/admin_check.php';
include_once '../../config/database.php';

// Verificamos que nos llegue el ID por la URL
if (isset($_GET['id'])) {
    $database = new Database();
    $db = $database->getConnection();

    try {
        // Preparamos la sentencia para eliminar
        $query = "DELETE FROM productos WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $_GET['id']);

        if ($stmt->execute()) {
            // Si se borra con éxito, volvemos al inventario con un aviso
            header("Location: admin_index.php?mensaje=eliminado");
            exit;
        }
    } catch (PDOException $e) {
        echo "Error al eliminar: " . $e->getMessage();
    }
} else {
    // Si alguien entra aquí sin ID, lo echamos al panel
    header("Location: admin_index.php");
    exit;
}
?>