<?php

/*
 [LUIS RAMOS] [ADAN ZAMORA]: Este código obtiene y muestra una lista de libros relacionados con un género específico utilizando la API de Google Books. 
 Implementa un sistema de caché local para optimizar el rendimiento y reducir solicitudes a la API.
 Los libros se presentan con detalles como título, descripción e imagen de portada, y se pueden personalizar con IDs estáticos
 según el género. En caso de errores con la API, se intenta usar datos del caché. También incluye un mecanismo para 
 manejar errores y mostrar mensajes amigables al usuario.
 */

 // Función para obtener libros por género
function getBooksByGenre($genre) {
    // Configuración de caché
    // Lo hicimos debido a que es recomendable ajustar la duración del caché según la frecuencia de actualización de los datos
    $cache_duration = 3600; // 1 hora en segundos
    $cache_file = "cache/books_" . md5($genre) . ".json"; // Nombre del archivo de caché
    $api_key = getenv('GOOGLE_BOOKS_API_KEY'); // Clave de la API de Google Books
    
    // Crear directorio de caché si no existe
    if (!file_exists('cache')) {
        mkdir('cache', 0777, true);
    }

    // Verificar si existe caché válido
    // Si el archivo de caché existe y no ha expirado, usarlo
    if (file_exists($cache_file) && (time() - filemtime($cache_file) < $cache_duration)) {
        $data = json_decode(file_get_contents($cache_file), true);
    } else {
        // Construir URL de la API
        $api_url = 'https://www.googleapis.com/books/v1/volumes';
        $params = http_build_query([
            'q' => 'subject:' . urlencode($genre), // Buscar libros por género
            'langRestrict' => 'es', // Restringir resultados a libros en español
            'key' => $api_key, // Clave de la API
            'maxResults' => 10 // Máximo número de resultados
        ]);
        
        // Realizar la solicitud con manejo de errores
        // Si hay un error, intentar usar caché antiguo si existe
        try {
            $context = stream_context_create([
                'http' => [
                    'timeout' => 5, // Tiempo de espera de 5 segundos
                    'header' => [
                        'User-Agent: PHP/BookRecommendationApp' // Agregar User-Agent a la solicitud
                    ]
                ]
            ]);
            
            // Suprimir errores con @
            $response = @file_get_contents($api_url . '?' . $params, false, $context);
            
            // Si hay un error, lanzar una excepción
            if ($response === false) {
                throw new Exception('Error al obtener datos de la API');
            }
            
            $data = json_decode($response, true);
            
            // Guardar en caché
            file_put_contents($cache_file, $response);
        } catch (Exception $e) {
            // Si hay error, intentar usar caché antiguo si existe
            if (file_exists($cache_file)) {
                $data = json_decode(file_get_contents($cache_file), true);
            } else {
                echo mostrarError("Error al obtener los libros: " . $e->getMessage());
                return;
            }
        }
    }

    // Diccionario de IDs estáticos
    // Nos sirven para mostrar libros estaticos que usamos como recomendaciones de libros para el usuario
    $book_ids = [
        "fiction" => ["0001", "0002", "0003", "0004", "0005", "0006", "0007", "0008", "0009", "0010"],
        "romance" => ["0011", "0012", "0013", "0014", "0015", "0016", "0017", "0018", "0019", "0020"],
        "classics" => ["0021", "0022", "0023", "0024", "0025", "0026", "0027", "0028", "0029", "0030"],
        "nonfiction" => ["0031", "0032", "0033", "0034", "0035", "0036", "0037", "0038", "0039", "0040"],
        "mystery" => ["0041", "0042", "0043", "0044", "0045", "0046", "0047", "0048", "0049", "0050"],
        "science fiction" => ["0051", "0052", "0053", "0054", "0055", "0056", "0057", "0058", "0059", "0060"],
        "fantasy" => ["0061", "0062", "0063", "0064", "0065", "0066", "0067", "0068", "0069", "0070"],
    ];

    // Mostrar resultados
    $output = "<div class='genre'>";
    
    if (isset($data['items']) && is_array($data['items'])) {
        foreach ($data['items'] as $index => $book) {
            $custom_id = $book_ids[$genre][$index] ?? null; // Obtener ID personalizado si existe
            $image_link = $book['volumeInfo']['imageLinks']['thumbnail'] ?? ''; // URL de la portada
            
            $output .= "<div class='book'>";
            $output .= "<h3>" . htmlspecialchars($book['volumeInfo']['title'] ?? 'Título desconocido', ENT_QUOTES, 'UTF-8') . // Título del libro
                      ($custom_id ? " (ID: $custom_id)" : "") . "</h3>"; // ID personalizado
            
            $description = $book['volumeInfo']['description'] ?? 'No hay descripción disponible.'; // Descripción del libro
            $output .= "<p>" . htmlspecialchars($description, ENT_QUOTES, 'UTF-8') . "</p>"; // Mostrar descripción
            
            if ($image_link) {
                // Cambiar tamaño de la imagen y centrar
                $output .= "<div style='text-align: center; margin-top: 10px;'>";
                $output .= "<img src='" . htmlspecialchars($image_link, ENT_QUOTES, 'UTF-8') . "' alt='Portada de " . htmlspecialchars($book['volumeInfo']['title'], ENT_QUOTES, 'UTF-8') . "' style='width:180px; height:auto;'>"; // Portada del libro
                $output .= "</div>";
            }
            $output .= "</div>";
        }
    } else {
        $output .= "<p>No se encontraron libros en esta categoría.</p>";
    }
    
    $output .= "</div>";
    echo $output;
}

// Función para mostrar errores
function mostrarError($mensaje) {
    echo "<div class='error'>$mensaje</div>";
}
?>

