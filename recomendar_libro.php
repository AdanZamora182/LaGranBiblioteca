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

// --- Funciones de Pertenencia Difusa Simples ---
// fp_baja: 1 si el valor es 0, 0 si el valor es 0.5 o más
function fp_baja($valor) {
    return max(0, 1 - 2 * $valor);
}
// fp_media: pico en 0.5 (valor 1), 0 en los extremos 0 y 1
function fp_media($valor) {
    return $valor <= 0.5 ? 2 * $valor : 2 * (1 - $valor);
}
// fp_alta: 1 si el valor es 1, 0 si el valor es 0.5 o menos
function fp_alta($valor) {
    return max(0, 2 * $valor - 1);
}
// -------------------------------------------------

// Obtener todos los libros de la base de datos
$sql_cols = implode(", ", $caracteristicas);
$sql = "SELECT id, titulo, autor, " . $sql_cols . " FROM libros_difusos";
$result = $conn->query($sql);

if (!$result) {
    echo json_encode(["error" => "Error al consultar la base de datos: " . $conn->error]);
    exit();
}

$librosCompatibles = [];
// Umbral mínimo de compatibilidad para considerar una recomendación
$umbralMinimoDeCompatibilidad = 3.0; // Este valor puede ajustarse según la sensibilidad deseada

while ($row = $result->fetch_assoc()) {
    $puntuacion_compatibilidad_libro = 0;

    foreach ($caracteristicas as $caract) {
        $preferencia_usuario_caract = isset($input[$caract]) ? floatval($input[$caract]) : 0;
        $valor_libro_caract = isset($row[$caract]) ? floatval($row[$caract]) : 0;
        $contribucion_caracteristica_actual = 0;

        // Fuzzificar preferencias del usuario y características del libro
        $u_baja = fp_baja($preferencia_usuario_caract);
        $u_media = fp_media($preferencia_usuario_caract);
        $u_alta = fp_alta($preferencia_usuario_caract);

        $l_baja = fp_baja($valor_libro_caract);
        $l_media = fp_media($valor_libro_caract);
        $l_alta = fp_alta($valor_libro_caract);

        // --- Aplicación de Reglas Difusas (Ejemplos) ---
        // Regla 1: Usuario quiere ALTO y Libro es ALTO (Fuerte coincidencia positiva)
        $activacion_r1 = min($u_alta, $l_alta);
        $contribucion_caracteristica_actual += $activacion_r1 * 2.0; // Peso de la regla

        // Regla 2: Usuario quiere MEDIO y Libro es MEDIO (Coincidencia positiva)
        $activacion_r2 = min($u_media, $l_media);
        $contribucion_caracteristica_actual += $activacion_r2 * 1.0;

        // Regla 3: Usuario quiere BAJO y Libro es BAJO (Coincidencia positiva, menos impacto)
        $activacion_r3 = min($u_baja, $l_baja);
        $contribucion_caracteristica_actual += $activacion_r3 * 0.5;
        
        // Regla 4: Usuario quiere ALTO pero Libro es BAJO (Fuerte desajuste negativo)
        $activacion_r4 = min($u_alta, $l_baja);
        $contribucion_caracteristica_actual -= $activacion_r4 * 1.5;

        // Regla 5: Usuario quiere BAJO pero Libro es ALTO (Desajuste negativo leve)
        // (Si al usuario NO le gusta algo y el libro lo tiene mucho)
        $activacion_r5 = min($u_baja, $l_alta);
        $contribucion_caracteristica_actual -= $activacion_r5 * 1.0;
        // -------------------------------------------------
        
        $puntuacion_compatibilidad_libro += $contribucion_caracteristica_actual;
    }

    $row['compatibilidad'] = $puntuacion_compatibilidad_libro;
    if ($puntuacion_compatibilidad_libro >= $umbralMinimoDeCompatibilidad) {
        $librosCompatibles[] = $row;
    }
}

// Ordenar libros por compatibilidad (solo los que superaron el umbral)
if (!empty($librosCompatibles)) {
    usort($librosCompatibles, function($a, $b) {
        return $b['compatibilidad'] <=> $a['compatibilidad'];
    });
    // Tomar los 5 mejores libros (o menos, si no hay 5 que superen el umbral)
    $mejoresLibros = array_slice($librosCompatibles, 0, 5);
} else {
    $mejoresLibros = [];
}


// Resultado
if (!empty($mejoresLibros)) {
    echo json_encode(["recomendaciones" => $mejoresLibros]);
} else {
    if ($result->num_rows > 0 && empty($librosCompatibles)) {
         echo json_encode(["error" => "No se encontraron libros con un grado de compatibilidad suficientemente alto según tus preferencias."]);
    } else if ($result->num_rows === 0) {
         echo json_encode(["error" => "No hay libros en la base de datos para realizar recomendaciones."]);
    }
    else {
         echo json_encode(["error" => "No se encontraron libros compatibles con tus preferencias."]);
    }
}

$conn->close();
?>