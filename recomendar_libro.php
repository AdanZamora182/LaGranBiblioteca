<?php
session_start(); // Inicia la sesión

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "No has iniciado sesión."]);
    exit();
}

require_once 'config.php'; // Conexión a la base de datos

// Obtener datos del usuario desde POST
$input = json_decode(file_get_contents("php://input"), true);

// Manejar el caso en el que no se envían datos (por ejemplo, al acceder desde el navegador)
if (!$input) {
    echo json_encode(["error" => "No se recibieron datos válidos."]);
    exit();
}

// Lista de características (deben coincidir con los campos de la tabla)
$caracteristicas = [
    "accion", "romance", "misterio", "fantasia", "filosofia", "ritmo", 
    "cienciaficcion", "reflexion", "emocional", "longitud", "juvenil", 
    "biografia", "historico", "mensaje", "suspenso", "humor", 
    "critica", "finalabierto"
];

// Obtener todos los libros de la base de datos
$sql = "SELECT id, titulo, autor, " . implode(", ", $caracteristicas) . " FROM libros_difusos";
$result = $conn->query($sql);

if (!$result) {
    echo json_encode(["error" => "Error al consultar la base de datos."]);
    exit();
}

$librosCompatibles = [];

while ($row = $result->fetch_assoc()) {
    $sumaCoincidencia = 0;

    foreach ($caracteristicas as $caract) {
        $usuario = isset($input[$caract]) ? floatval($input[$caract]) : 0;
        $libro = isset($row[$caract]) ? floatval($row[$caract]) : 0;
        $sumaCoincidencia += min($usuario, $libro);
    }

    $row['compatibilidad'] = $sumaCoincidencia;
    $librosCompatibles[] = $row;
}

// Ordenar libros por compatibilidad
usort($librosCompatibles, function($a, $b) {
    return $b['compatibilidad'] <=> $a['compatibilidad'];
});

// Tomar los 5 mejores libros
$mejoresLibros = array_slice($librosCompatibles, 0, 5);

// Resultado con múltiples recomendaciones
if (!empty($mejoresLibros)) {
    echo json_encode(["recomendaciones" => $mejoresLibros]);
} else {
    echo json_encode(["error" => "No se encontraron libros compatibles."]);
}

$conn->close();
?>