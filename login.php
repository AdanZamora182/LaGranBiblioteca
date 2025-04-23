<?php
// [ALEJANDRO MADRIGAL]: Código para inciar sesión

session_start();
include 'config.php';

// Verificar si el usuario ya inició sesión
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $contraseña = $_POST["contraseña"];

    // Consulta para verificar si el usuario existe en la base de datos
    $sql = "SELECT id, nombre, email FROM usuarios WHERE email='$email' AND contraseña='$contraseña'"; //
    $result = mysqli_query($conn, $sql);

    // Si se encontró un usuario, iniciar sesión y redirigir a la página de inicio
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $row['id']; // Almacena el ID de usuario en la sesión
        $_SESSION['username'] = $row['nombre']; // Almacena el nombre de usuario en la sesión
        $_SESSION['user_email'] = $row['email']; // Almacena el correo electrónico en la sesión
        header("Location: home.php");
        exit();
    } else {
        $error = "Correo electrónico o contraseña incorrectos";
    }

    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Raleway:wght@400;700&display=swap');
        
        /* Estilos generales */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: 'Raleway', sans-serif;
            background: linear-gradient(120deg, #f0f2f5 40%, #ffffff 100%);
            color: #333;
        }

        /* Estilos para el contenedor principal, contenido y pie de página */
        .wrapper {
            min-height: 100%;
            display: flex;
            flex-direction: column;
        }

        .content {
            flex: 1;
            max-width: 600px;
            margin: 180px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .header {
            background-color: #2C3E50;
            color: white;
            padding: 20px 0;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            font-size: 2.5em;
            margin: 0;
        }

        /* Estilos para el formulario de inicio de sesión */
        h2 {
            font-size: 2em;
            color: #2C3E50;
            margin-bottom: 30px;
        }

        form {
            width: 100%;
        }

        label {
            font-size: 1.2em;
            color: #34495E;
            margin-bottom: 10px;
            display: block;
            text-align: left;
        }

        input[type="email"],
        input[type="password"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1.1em;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #E74C3C;
            color: white;
            padding: 15px 30px;
            font-size: 1.2em;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #C0392B;
            transform: scale(1.05);
        }

        /* Estilos para el mensaje de error */
        .error-message {
            color: #E74C3C;
            margin-top: 20px;
            font-size: 1.1em;
        }

        .footer {
            background-color: #2C3E50;
            color: #ecf0f1;
            padding: 30px 0;
            text-align: center;
        }

        .footer p {
            margin: 0;
        }
    </style>
</head>
<body>

    <div class="wrapper">
        <div class="header">
            <h1>La Gran Biblioteca</h1>
        </div>

        <div class="content">
            <h2>Iniciar Sesión</h2>
            
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST"> <!-- Formulario de inicio de sesión -->
                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" name="email" required>
                
                <label for="contraseña">Contraseña:</label>
                <input type="password" id="contraseña" name="contraseña" required>
                
                <input type="submit" value="Iniciar Sesión">
            </form>

            <!-- Mostrar mensaje de error si existe -->
            <?php
            if (isset($error)) {
                echo "<p class='error-message'>$error</p>";
            }
            ?>

            <!-- Enlace para regresar a la página principal -->
            <a href="index.php" class="btn btn-secondary mt-3">
                <i class="fas fa-arrow-left"></i> Regresar
            </a>
        </div>

        <div class="footer">
            <p>&copy; 2024 La Gran Biblioteca. Todos los derechos reservados.</p>
        </div>
    </div>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>
</html>