<?php

/**
 * [ADAN ZAMORA] [LUIS RAMOS]: Este código proporciona dos funcionalidades principales relacionadas con libros y reseñas:
 * 1. **Buscar libros por título utilizando la API de Google Books**:
 *    - Realiza una solicitud a la API de Google Books con un título proporcionado como parámetro.
 *    - Recupera información del primer libro coincidente, incluyendo el título, ID, y enlace de la portada.
 *    - Genera un formulario en HTML para seleccionar el libro encontrado y muestra su información de manera visual.
 *    - Muestra un mensaje si no se encuentran resultados.
 * 2. **Buscar reseñas de un usuario por término de búsqueda**:
 *    - Consulta nuestra base de datos para encontrar reseñas realizadas por un usuario específico, que coincidan parcialmente con un término de búsqueda en el título del libro.
 *    - Devuelve las reseñas encontradas en forma de un arreglo asociativo.
 * Además, incluye la lógica para manejar una solicitud GET con el título de un libro y mostrar sus resultados.
 */

function getBooksByTitle($title) {
    $apiKey = 'AIzaSyCzQE7O9KMS_5H5DIVoLWs-B33jLY3to8Q'; // Clave de la API de Google Books 
    $url = "https://www.googleapis.com/books/v1/volumes?q=" . urlencode($title) . "&key=" . $apiKey; // URL de la API

    // Realiza la solicitud a la API
    $response = @file_get_contents($url);
    $data = json_decode($response, true);

    // Genera el HTML para mostrar el libro
    $output = "<div class='genre'>";

    // Verifica si se encontraron libros con el título proporcionado 
    if (isset($data['items']) && is_array($data['items'])) {
        // Solo mostramos el primer libro
        $book = $data['items'][0];
        // Extraemos la información relevante del libro
        $id_libro = $book['id']; 
        $image_link = $book['volumeInfo']['imageLinks']['thumbnail'] ?? '';
        $title = htmlspecialchars($book['volumeInfo']['title'] ?? 'Título desconocido', ENT_QUOTES, 'UTF-8');

        // Genera el HTML para mostrar el libro
        $output .= "<div class='book'>";
        $output .= "<h3>$title</h3>";
        $output .= "<input type='radio' name='id_libro' value='$id_libro' required> Seleccionar";
        $output .= "<input type='hidden' name='nombre_libro' value='$title'>"; // Campo oculto para el nombre del libro
        
        // Muestra la portada del libro si está disponible
        if ($image_link) {
            $output .= "<div style='text-align: center; margin-top: 10px;'>";
            $output .= "<img src='" . htmlspecialchars($image_link, ENT_QUOTES, 'UTF-8') . "' alt='Portada de $title' style='width:180px; height:auto;'>"; 
            $output .= "</div>";
        }
        $output .= "</div>";
    } else {
        $output .= "<p>No se encontraron libros con ese título.</p>";
    }

    $output .= "</div>";
    echo $output;
}

// Función para buscar reseñas de un usuario por un término de búsqueda 
function searchUserReviews($userId, $searchTerm) {
    include 'config.php';
    $searchTerm = '%' . $searchTerm . '%'; // Agregar comodines para buscar coincidencias parciales
    $sql = "SELECT * FROM reseñas WHERE id_usuario = ? AND nombre_libro LIKE ?"; // Consulta SQL con un parámetro de búsqueda
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $userId, $searchTerm); // Enlazar los parámetros
    $stmt->execute();
    $result = $stmt->get_result(); // Obtener los resultados
    $reviews = [];
    while ($row = $result->fetch_assoc()) {  // Iterar sobre los resultados
        $reviews[] = $row;
    }
    return $reviews;
}

// Función para buscar libros y devolver datos en formato JSON para autocompletado
function searchBooksForAutocomplete($query) {
    $apiKey = 'AIzaSyCzQE7O9KMS_5H5DIVoLWs-B33jLY3to8Q';
    $url = "https://www.googleapis.com/books/v1/volumes?q=" . urlencode($query) . "&key=" . $apiKey . "&maxResults=10";
    
    $response = @file_get_contents($url);
    $data = json_decode($response, true);
    
    $books = [];
    
    if (isset($data['items']) && is_array($data['items'])) {
        foreach ($data['items'] as $book) {
            $title = $book['volumeInfo']['title'] ?? 'Título desconocido';
            $authors = isset($book['volumeInfo']['authors']) ? implode(', ', $book['volumeInfo']['authors']) : 'Autor desconocido';
            $id = $book['id'];
            
            $books[] = [
                'id' => $id,
                'title' => $title,
                'authors' => $authors,
                'display' => $title . ' - ' . $authors
            ];
        }
    }
    
    return $books;
}

// Comprueba si se ha recibido un título de un libro en la solicitud
if (isset($_GET['title'])) {
    $title = $_GET['title'];
    getBooksByTitle($title);
}

// Nueva funcionalidad para autocompletado
if (isset($_GET['autocomplete']) && isset($_GET['query'])) {
    $query = $_GET['query'];
    $books = searchBooksForAutocomplete($query);
    header('Content-Type: application/json');
    echo json_encode($books);
    exit;
}
?>
