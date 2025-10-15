<?php

/**
 * [LUIS RAMOS] [ADAN ZAMORA]: Este código implementa una página que permite a los usuarios gestionar sus reseñas de libros. 
 * Incluye funcionalidades para verificar el inicio de sesión, paginar las reseñas (7 por página), 
 * realizar búsquedas basadas en el nombre del libro, y navegar directamente a la página de una 
 * reseña encontrada. También permite la edición y eliminación de reseñas. La interfaz está 
 * diseñada con Bootstrap y JQuery para una experiencia visual mejorada, incluyendo autocompletado 
 * para el campo de búsqueda.
 */

session_start();
include 'config.php';

// Verifica si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['user_id']; 

// Número de reseñas por página
$reviews_per_page = 7;

// Obtener el número total de reseñas
$sql_count = "SELECT COUNT(*) as total FROM reseñas WHERE id_usuario = ?";
$stmt_count = $conn->prepare($sql_count);
$stmt_count->bind_param("i", $id_usuario);
$stmt_count->execute();
$result_count = $stmt_count->get_result(); 
$row_count = $result_count->fetch_assoc();
$total_reviews = $row_count['total'];

// Calcular el número total de páginas
$total_pages = $total_reviews > 0 ? ceil($total_reviews / $reviews_per_page) : 1;

// Obtener la página actual de la URL, si no está presente, por defecto es la página 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
if ($page > $total_pages) $page = $total_pages;

// Calcular el desplazamiento para la consulta SQL - solo si hay reseñas
$offset = $total_reviews > 0 ? ($page - 1) * $reviews_per_page : 0;

$reviews = [];

// Solo ejecutar la consulta si hay reseñas
if ($total_reviews > 0) {
    // Consulta para obtener las reseñas del usuario con límite y desplazamiento
    $sql = "SELECT * FROM reseñas WHERE id_usuario = ? LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $id_usuario, $reviews_per_page, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }
}

// Buscar reseñas por nombre de libro si se ha enviado un término de búsqueda
$search_term = isset($_GET['search']) ? $_GET['search'] : '';
if ($search_term && !isset($_GET['search_processed']) && $total_reviews > 0) {
    $sql_search = "SELECT id FROM reseñas WHERE id_usuario = ? AND nombre_libro LIKE ?";
    $stmt_search = $conn->prepare($sql_search);
    $search_term_like = '%' . $search_term . '%';
    $stmt_search->bind_param("is", $id_usuario, $search_term_like);
    $stmt_search->execute();
    $result_search = $stmt_search->get_result();
    if ($result_search->num_rows > 0) {
        $search_result = $result_search->fetch_assoc();
        $search_id = $search_result['id'];

        // Obtener la posición de la reseña en la lista ordenada por ID para calcular la páginación
        $sql_position = "SELECT COUNT(*) as position FROM reseñas WHERE id_usuario = ? AND id <= ?";
        $stmt_position = $conn->prepare($sql_position);
        $stmt_position->bind_param("ii", $id_usuario, $search_id);
        $stmt_position->execute();
        $result_position = $stmt_position->get_result();
        $row_position = $result_position->fetch_assoc();
        $position = $row_position['position'];

        // Calcular la página en la que se encuentra la reseña
        $page = ceil($position / $reviews_per_page);
        header("Location: my_reviews.php?page=$page&search=$search_term&search_processed=1");
        exit();
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Reseñas</title>
    <link rel="icon" href="iconolibros.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
    <style>
        /* Estilos básicos */
        @import url('https://fonts.googleapis.com/css2?family=Raleway:wght@400;700&display=swap');
        body {
            font-family: 'Raleway', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(120deg, #f0f2f5 40%, #ffffff 100%);
            color: #333;
        }

        /* Estilos para el contenedor principal */
        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h1 {
            text-align: center;
            color: #2C3E50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #2C3E50;
            color: white;
        }

        a {
            color: #E74C3C;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Estilos para los botones de navegación */
        .back-home-btn, .back-reviews-btn {
            position: absolute;
            top: 20px;
            color: #fff;
            background-color: #ff8c00;
            padding: 10px;
            border-radius: 50%;
            text-decoration: none;
            border: none;
            transition: background-color 0.3s ease, transform 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none; /* Quitar subrayado */
        }

        .back-home-btn {
            left: 20px;
        }

        .back-reviews-btn {
            left: 70px; /* Ajusta la posición según sea necesario */
        }

        .back-home-btn:hover, .back-reviews-btn:hover {
            background-color: #e67e22 !important;
            transform: scale(1.1);
            color: #fff;
            text-decoration: none; /* Quitar subrayado */
        }

        .back-home-btn i, .back-reviews-btn i {
            font-size: 20px;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 5px 10px;
            margin-right: 10px;
            margin-bottom: 5px;
        }

        .btn-action i {
            margin-right: 5px;
        }

        .btn-group {
            display: flex;
            gap: 10px;
        }

        /* Estilo para los mensajes de alerta */
        .alert-message {
            margin-bottom: 20px;
            padding: 10px 15px;
            border-radius: 5px;
            animation: fadeOut 5s forwards;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        @keyframes fadeOut {
            0% { opacity: 1; }
            80% { opacity: 1; }
            100% { opacity: 0; }
        }
    </style>
</head>
<body>
    <a href="home.php" class="back-home-btn">
        <i class="fas fa-home"></i>
    </a>
    <a href="reviews.php" class="back-reviews-btn">
        <i class="fas fa-arrow-left"></i>
    </a>
    <div class="container">
        <h1>Mis Reseñas</h1>
        
        <?php
        // Mostrar mensaje de éxito si existe
        if (isset($_SESSION['review_message'])): ?>
            <div class="alert-message alert-<?php echo $_SESSION['review_message_type']; ?>">
                <?php echo $_SESSION['review_message']; ?>
            </div>
            <?php 
            // Eliminar el mensaje para que no aparezca en futuras cargas
            unset($_SESSION['review_message']); 
            unset($_SESSION['review_message_type']);
            ?>
        <?php endif; ?>
        
        <?php if ($total_reviews > 0): ?>
            <form method="GET" action="my_reviews.php" class="form-inline mb-3">
                <input type="text" name="search" id="search" class="form-control mr-sm-2" placeholder="Buscar reseñas..." value="<?php echo htmlspecialchars($search_term); ?>">
                <button type="submit" class="btn btn-outline-success my-2 my-sm-0">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        <?php endif; ?>
        
        <?php if (count($reviews) > 0): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nombre del Libro</th>
                        <th>Calificación</th>
                        <th>Comentario</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reviews as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['nombre_libro']); ?></td> <!-- Nombre del libro -->
                            <td><?php echo htmlspecialchars($row['calificacion']); ?></td> <!-- Calificación -->
                            <td><?php echo htmlspecialchars($row['comentario']); ?></td> <!-- Comentario -->
                            <td>
                                <div class="btn-group">
                                    <a href="edit_review.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm btn-action">
                                        <i class="fas fa-pencil-alt"></i> Editar
                                    </a>
                                    <a href="delete_review.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm btn-action" 
                                       onclick="return confirm('¿Estás seguro de que deseas eliminar esta reseña?');">
                                        <i class="fas fa-trash-alt"></i> Eliminar
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <!-- Paginación -->
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <!-- Botón para ir a la página anterior -->
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Anterior">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <!-- Números de página -->
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <!-- Botón para ir a la página siguiente -->
                    <?php if ($page < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Siguiente">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php else: ?>
            <p class="text-center" style="font-size: 1.2em; color: #666; margin-top: 50px;">Por el momento no tienes ninguna reseña para mostrar.</p>
            <?php if ($total_reviews == 0): ?>
                <div class="text-center mt-4">
                    <a href="add_review.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Agregar tu primera reseña
                    </a>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script>
        $(function() { 
            // Verificar si hay un parámetro de edición exitosa
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('edited') === 'success') {
                // Mostrar alerta del navegador
                alert('¡La reseña se ha actualizado correctamente!');
                
                // Eliminar el parámetro de la URL para evitar que aparezca la alerta al recargar
                const newUrl = window.location.pathname + window.location.search.replace(/[?&]edited=success/, '');
                window.history.replaceState({}, document.title, newUrl);
            }
            
            $("#search").autocomplete({
                source: function(request, response) {
                    $.ajax({ // Realizar una petición AJAX al archivo search_books.php
                        type: "POST",
                        url: "search_books.php",
                        dataType: "json",
                        data: {
                            term: request.term,
                            user_id: <?php echo $id_usuario; ?>
                        },
                        success: function(data) { // Mostrar los resultados en el autocompletado
                            response(data);
                        }
                    });
                },
                minLength: 2 // Mostrar resultados después de escribir al menos 2 caracteres
            });
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

