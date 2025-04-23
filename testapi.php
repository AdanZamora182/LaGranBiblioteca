<?php
// [TRISTAN EGUIA] [ALEJANDRO MADRIGAL]: Este código fue creado para probar la interacción con la API de Google Books y explorar los datos que proporciona. 

// Función para hacer la llamada a la API
function getBookData($searchQuery) {
    $apiUrl = "https://www.googleapis.com/books/v1/volumes?q=" . urlencode($searchQuery);
    $response = file_get_contents($apiUrl);
    return json_decode($response, true);
}

// Función para mostrar los datos de forma estructurada 
function displayBookData($bookData) {
    if (isset($bookData['items']) && !empty($bookData['items'])) { // Verificar si hay resultados
        foreach ($bookData['items'] as $book) { // Iterar sobre los resultados
            echo "<div style='border: 1px solid #ccc; margin: 10px; padding: 10px;'>";
            echo "<h3>Datos disponibles para: " . $book['volumeInfo']['title'] . "</h3>";
            echo "<pre>";
            
            // Información básica del volumen
            echo "<strong>ID del libro:</strong> " . $book['id'] . "\n";
            
            // Información del volumen (volumeInfo)
            echo "\n<strong>INFORMACIÓN DEL VOLUMEN:</strong>\n";
            if (isset($book['volumeInfo'])) { //
                $volumeInfo = $book['volumeInfo'];
                echo "- Título: " . ($volumeInfo['title'] ?? 'No disponible') . "\n"; 
                echo "- Subtítulo: " . ($volumeInfo['subtitle'] ?? 'No disponible') . "\n";
                echo "- Autores: " . (isset($volumeInfo['authors']) ? implode(", ", $volumeInfo['authors']) : 'No disponible') . "\n";
                echo "- Editorial: " . ($volumeInfo['publisher'] ?? 'No disponible') . "\n";
                echo "- Fecha de publicación: " . ($volumeInfo['publishedDate'] ?? 'No disponible') . "\n";
                echo "- Descripción: " . (isset($volumeInfo['description']) ? substr($volumeInfo['description'], 0, 150) . "..." : 'No disponible') . "\n";
                echo "- Número de páginas: " . ($volumeInfo['pageCount'] ?? 'No disponible') . "\n";
                echo "- Categorías: " . (isset($volumeInfo['categories']) ? implode(", ", $volumeInfo['categories']) : 'No disponible') . "\n";
                echo "- Idioma: " . ($volumeInfo['language'] ?? 'No disponible') . "\n";
            }
            
            // Información de venta (saleInfo) 
            echo "\n<strong>INFORMACIÓN DE VENTA:</strong>\n";
            if (isset($book['saleInfo'])) {
                $saleInfo = $book['saleInfo'];
                echo "- País: " . ($saleInfo['country'] ?? 'No disponible') . "\n";
                echo "- Disponibilidad: " . ($saleInfo['saleability'] ?? 'No disponible') . "\n";
                if (isset($saleInfo['listPrice'])) {
                    echo "- Precio: " . $saleInfo['listPrice']['amount'] . " " . $saleInfo['listPrice']['currencyCode'] . "\n";
                }
            }
            
            // Información de acceso (accessInfo)
            echo "\n<strong>INFORMACIÓN DE ACCESO:</strong>\n";
            if (isset($book['accessInfo'])) {
                $accessInfo = $book['accessInfo'];
                echo "- País: " . ($accessInfo['country'] ?? 'No disponible') . "\n";
                echo "- Vista previa: " . ($accessInfo['viewability'] ?? 'No disponible') . "\n";
                echo "- PDF disponible: " . (isset($accessInfo['pdf']['isAvailable']) ? ($accessInfo['pdf']['isAvailable'] ? 'Sí' : 'No') : 'No disponible') . "\n";
                echo "- EPUB disponible: " . (isset($accessInfo['epub']['isAvailable']) ? ($accessInfo['epub']['isAvailable'] ? 'Sí' : 'No') : 'No disponible') . "\n";
            }
            
            // Enlaces (si están disponibles)
            if (isset($book['volumeInfo']['imageLinks'])) {
                echo "\n<strong>ENLACES DE IMÁGENES:</strong>\n";
                foreach ($book['volumeInfo']['imageLinks'] as $type => $url) {
                    echo "- $type: $url\n";
                }
            }
            
            echo "</pre>";
            echo "</div>";
        }
    } else {
        echo "<p>No se encontraron resultados.</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Explorador de API de Google Books</title> 
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        pre { white-space: pre-wrap; }
        .search-form { margin-bottom: 20px; }
    </style>
</head>
<body>
    <h1>Explorador de API de Google Books</h1>
    
    <div class="search-form">
        <form method="GET">
            <input type="text" name="q" placeholder="Buscar libro..." value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
            <button type="submit">Buscar</button>
        </form>
    </div>

    <?php
    if (isset($_GET['q'])) {
        $bookData = getBookData($_GET['q']);
        displayBookData($bookData);
    }
    ?>
</body>
</html>