<?php

/**
 * [ADAN ZAMORA]: Este código genera una página web dinámica que muestra las reseñas de un libro específico. 
 * Incluye las siguientes funcionalidades:
 * - Obtiene el promedio de calificación y el total de reseñas de la base de datos.
 * - Muestra las reseñas en tarjetas con calificaciones en formato de estrellas.
 * - Implementa paginación para navegar entre múltiples reseñas.
 * - Valida y protege los datos de entrada mediante el uso de consultas SQL preparadas.
 * - Calcula dinámicamente el desplazamiento (offset) para cada página de reseñas.
 */

include 'config.php';

// Cambiar para buscar por ID del libro en lugar del título
$bookId = isset($_GET['book_id']) ? $_GET['book_id'] : '';
$bookTitle = isset($_GET['book']) ? $_GET['book'] : ''; // Mantener para compatibilidad
$limit = 5;  // Número de reseñas por página
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Página actual
$offset = ($page - 1) * $limit; // Desplazamiento para la consulta SQL

// Debug: mostrar el ID que se está buscando
// echo "<!-- Buscando reseñas para ID: " . htmlspecialchars($bookId) . " -->";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reseñas - La Gran Biblioteca</title>
    
    <!-- Enlace a Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Enlace a Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>

        /* Estilos generales */
        /* Estilos para las estrellas */
        .star-filled {
            color: #ffc107;
        }
        .star-empty {
            color: #dcdcdc;
        }

        /* Estilos para las reseñas */
        .review-card {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
        }
        .review-card h5 {
            font-weight: bold;
        }
        .review-card p {
            margin: 5px 0;
        }

        /* Estilos para la paginación */
        .average-rating {
            font-size: 1.2rem;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <?php
    if (!$conn) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    // Si tenemos ID del libro, buscar por ID; si no, usar el título como fallback
    if (!empty($bookId)) {
        // Búsqueda por ID del libro
        $avgSql = "SELECT COUNT(*) as total, AVG(calificacion) as promedio FROM reseñas WHERE id_libro = ?";
        $avgStmt = $conn->prepare($avgSql);
        $avgStmt->bind_param("s", $bookId);
        $avgStmt->execute();
        $avgResult = $avgStmt->get_result();
        $avgData = $avgResult->fetch_assoc();
        $totalReviews = $avgData['total'];
    } else {
        // Fallback: búsqueda por título (código anterior)
        $avgSql = "SELECT COUNT(*) as total, AVG(calificacion) as promedio FROM reseñas WHERE nombre_libro = ?";
        $avgStmt = $conn->prepare($avgSql);
        $avgStmt->bind_param("s", $bookTitle);
        $avgStmt->execute();
        $avgResult = $avgStmt->get_result();
        $avgData = $avgResult->fetch_assoc();
        $totalReviews = $avgData['total'];
        
        // Si no se encuentran resultados, intentar búsqueda parcial
        if ($totalReviews == 0) {
            $searchPattern = '%' . $bookTitle . '%';
            $avgSql = "SELECT COUNT(*) as total, AVG(calificacion) as promedio FROM reseñas WHERE nombre_libro LIKE ?";
            $avgStmt = $conn->prepare($avgSql);
            $avgStmt->bind_param("s", $searchPattern);
            $avgStmt->execute();
            $avgResult = $avgStmt->get_result();
            $avgData = $avgResult->fetch_assoc();
            $totalReviews = $avgData['total'];
        }
    }
    
    $averageRating = round($avgData['promedio'], 1);

    // Calcular el número total de páginas (redondeando hacia arriba)
    $totalPages = ceil($totalReviews / $limit);

    // Asegurarse de que la página actual es válida
    if ($page < 1) $page = 1;
    if ($page > $totalPages && $totalPages > 0) $page = $totalPages;

    // Recalcular el offset después de validar la página
    $offset = ($page - 1) * $limit;

    // Mostrar el promedio de calificación
    echo "<div class='average-rating'>";
    echo "<strong>Calificación promedio:</strong> ";
   
    // Estrellas de calificación promedio (redondeando al entero más cercano)
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= floor($averageRating)) {
            echo "<span class='star-filled'><i class='fas fa-star'></i></span>";
        } elseif ($i == ceil($averageRating) && $averageRating - floor($averageRating) >= 0.5) {
            echo "<span class='star-filled'><i class='fas fa-star-half-alt'></i></span>";
        } else {
            echo "<span class='star-empty'><i class='far fa-star'></i></span>";
        }
    }
    // Mostrar el promedio de calificación
    echo " (" . htmlspecialchars($averageRating) . "/5)";
    echo "</div>";

    // Consulta SQL para obtener reseñas con límite y desplazamiento (para la paginación) 
    if ($totalReviews > 0) {
        if (!empty($bookId)) {
            // Buscar por ID del libro
            $sql = "SELECT r.id, r.calificacion, r.comentario, u.nombre AS nombre_usuario
                    FROM reseñas r
                    LEFT JOIN usuarios u ON r.id_usuario = u.id
                    WHERE r.id_libro = ?
                    ORDER BY r.id DESC
                    LIMIT ? OFFSET ?";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sii", $bookId, $limit, $offset);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            // Fallback: usar la lógica anterior por título
            $sql = "SELECT r.id, r.calificacion, r.comentario, u.nombre AS nombre_usuario
                    FROM reseñas r
                    LEFT JOIN usuarios u ON r.id_usuario = u.id
                    WHERE r.nombre_libro = ?
                    ORDER BY r.id DESC
                    LIMIT ? OFFSET ?";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sii", $bookTitle, $limit, $offset);
            $stmt->execute();
            $result = $stmt->get_result();
            
            // Si no hay resultados con búsqueda exacta, usar búsqueda parcial
            if ($result->num_rows == 0) {
                $searchPattern = '%' . $bookTitle . '%';
                $sql = "SELECT r.id, r.calificacion, r.comentario, u.nombre AS nombre_usuario
                        FROM reseñas r
                        LEFT JOIN usuarios u ON r.id_usuario = u.id
                        WHERE r.nombre_libro LIKE ?
                        ORDER BY r.id DESC
                        LIMIT ? OFFSET ?";
                
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sii", $searchPattern, $limit, $offset);
                $stmt->execute();
                $result = $stmt->get_result();
            }
        }

        // Sección de reseñas individuales
        echo "<section class='reviews-section'>";
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='review-card'>";
                echo "<h5>Usuario: " . htmlspecialchars($row['nombre_usuario']) . "</h5>";
                
                // Calificación en estrellas (de 1 a 5)
                echo "<p><strong>Calificación:</strong> ";
                for ($i = 1; $i <= 5; $i++) {
                    echo $i <= $row['calificacion'] ?
                         "<span class='star-filled'><i class='fas fa-star'></i></span>" :
                         "<span class='star-empty'><i class='far fa-star'></i></span>";
                }
                echo " (" . htmlspecialchars($row['calificacion']) . "/5)</p>";
                
                // Comentario
                if (isset($row['comentario'])) {
                    echo "<p><strong>Comentario:</strong> " . htmlspecialchars($row['comentario']) . "</p>";
                }
                echo "</div>";
            }
        }
        echo "</section>";

        // Modificar la sección de paginación para incluir el book_id
        if ($totalPages > 1) {
            echo "<nav aria-label='Page navigation'>";
            echo "<ul class='pagination justify-content-center mt-4'>";
            
            // Construir parámetros para la URL
            $urlParams = !empty($bookId) ? "book_id=" . urlencode($bookId) : "book=" . urlencode($bookTitle);
            
            // Botón Anterior 
            if ($page > 1) {
                echo "<li class='page-item'>";
                echo "<a class='page-link' href='?{$urlParams}&page=" . ($page - 1) . "' data-page='" . ($page - 1) . "' aria-label='Anterior'>";
                echo "<span aria-hidden='true'>&laquo;</span></a></li>";
            }
            
            // Números de página 
            for ($i = 1; $i <= $totalPages; $i++) {
                echo "<li class='page-item " . ($i == $page ? 'active' : '') . "'>";
                echo "<a class='page-link' href='?{$urlParams}&page=$i' data-page='$i'>$i</a></li>";
            }
            
            // Botón Siguiente
            if ($page < $totalPages) {
                echo "<li class='page-item'>";
                echo "<a class='page-link' href='?{$urlParams}&page=" . ($page + 1) . "' data-page='" . ($page + 1) . "' aria-label='Siguiente'>";
                echo "<span aria-hidden='true'>&raquo;</span></a></li>";
            }
            
            echo "</ul></nav>";
        }
    } else {
        echo "<div class='alert alert-info'>No hay reseñas disponibles para este libro.</div>";
        
        // Debug: mostrar qué libros están en la base de datos
        echo "<!-- Debug: IDs de libros en la base de datos: ";
        $debugSql = "SELECT DISTINCT id_libro, nombre_libro FROM reseñas LIMIT 10";
        $debugResult = $conn->query($debugSql);
        if ($debugResult) {
            while ($debugRow = $debugResult->fetch_assoc()) {
                echo "ID: " . htmlspecialchars($debugRow['id_libro']) . " - " . htmlspecialchars($debugRow['nombre_libro']) . " | ";
            }
        }
        echo " -->";
    }
    ?>
</div>

<!-- Enlace a Bootstrap JS y dependencias -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>