<?php
//[TRISTAN EGUIA]: Código para registrar un usuario y guardarlo en la base de datos
include 'config.php';

// Procesar el formulario cuando se envíe el registro de usuario 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $email = $_POST["email"];
    $contraseña = $_POST["contraseña"];

    // Insertar los datos del usuario en la base de datos
    $sql = "INSERT INTO usuarios (nombre, email, contraseña) VALUES ('$nombre', '$email', '$contraseña')";
    
    // Ejecutar la consulta
    if (mysqli_query($conn, $sql)) {
        // Redirigir al index después del registro exitoso
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    
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

        /* Estilos para el contenedor principal */
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
            font-size: 2em;
            margin: 0;
        }

        /* Estilos para el contenido */
        h2 {
            font-size: 1.8em; /* Título ajustado */
            color: #2C3E50;
            margin-bottom: 20px;
        }

        form {
            width: 100%;
        }

        label {
            font-size: 1.1em; /* Tamaño de fuente ajustado */
            color: #34495E;
            margin-bottom: 8px;
            display: block;
            text-align: left;
        }

        /* Estilos para los inputs y botón */
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: calc(100% - 16px); /* Ajustamos el ancho de los inputs */
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1em; /* Ajustamos el tamaño del texto */
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #E74C3C;
            color: white;
            padding: 10px 20px;
            font-size: 1.1em;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #C0392B;
            transform: scale(1.05);
        }

        /* Estilos para el footer */
        .footer {
            background-color: #2C3E50;
            color: #ecf0f1;
            padding: 20px 0;
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
            <h2>Registro de Usuario</h2>
            
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST"> <!--- Formulario de registro -->
                
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>
                
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                
                <label for="contraseña">Contraseña:</label>
                <input type="password" id="contraseña" name="contraseña" required>
                
                <input type="submit" value="Registrar">
            </form>
            <a href="index.php" class="btn btn-secondary mt-3"> <!--- Botón para regresar al index -->
                <i class="fas fa-arrow-left"></i> Regresar
            </a>
        </div>

        <!--- Pie de pagina -->
        <div class="footer">
            <p>&copy; 2024 La Gran Biblioteca. Todos los derechos reservados.</p>
        </div>
    </div>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>
</html>
