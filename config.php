<?php
// [TRISTAN EGUIA]: Código para conectar a nuestra base de datos

// Datos de conexión
$servername = "recomendaciones-libros.c01os0m2g3ry.us-east-1.rds.amazonaws.com";
$username = "admin";
$password = "LaGranBiblioteca123";
$dbname = "recomendaciones_libros";
$port = 3306;


// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>