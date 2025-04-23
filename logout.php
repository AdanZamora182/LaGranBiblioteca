<?php
// [ALEJANDRO MADRIGAL]: Código para cerrar sesión

session_start();

// Destruir todas las variables de sesión
$_SESSION = array();

// Si se desea destruir la sesión completamente, se debe borrar también la cookie de sesión.
// Esto borra la sesión pero no la información de la sesión.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params(); // Obtener los parámetros de la cookie de sesión
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finalmente, destruir la sesión
session_destroy();

// Redirigir al index o a donde desees
header("Location: index.php");
exit(); // Asegurar que el script se detenga después de redirigir
?>