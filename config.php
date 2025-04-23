<?php
// [TRISTAN EGUIA]: Código para conectar a nuestra base de datos

// Datos de conexión
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "recomendaciones_libros";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>