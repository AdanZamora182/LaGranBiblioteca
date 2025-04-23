<?php
// [ALEJANDRO MADRIGAL]: Código para eliminar una reseña

session_start();
include 'config.php';

// Verifica si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id_resena = $_GET['id']; // Obtiene el ID de la reseña a eliminar

// Elimina la reseña de la base de datos
$sql = "DELETE FROM reseñas WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_resena);

if ($stmt->execute()) {
    header("Location: my_reviews.php"); // Redirigir a la lista de reseñas
    exit();
} else {
    echo "Error al eliminar la reseña: " . $stmt->error; // Mostrar mensaje de error
}

mysqli_close($conn);
?>
