<?php
//[TRISTAN EGUIA]: Código para registrar un usuario y guardarlo en la base de datos
include 'config.php';

// Variable para almacenar mensajes de error
$error_message = "";

// Procesar el formulario cuando se envíe el registro de usuario 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $email = $_POST["email"];
    $contraseña = $_POST["contraseña"];
    $verificar_contraseña = $_POST["verificar_contraseña"];

    // Validar que las contraseñas coincidan
    if ($contraseña !== $verificar_contraseña) {
        $error_message = "Las contraseñas no coinciden. Por favor, verifica que ambas contraseñas sean iguales.";
    } else {
        // Hashear la contraseña para mayor seguridad
        $contraseña_hash = password_hash($contraseña, PASSWORD_DEFAULT);
        
        // Usar prepared statements para evitar SQL injection
        $stmt = mysqli_prepare($conn, "INSERT INTO usuarios (nombre, email, contraseña) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sss", $nombre, $email, $contraseña_hash);
        
        // Ejecutar la consulta
        if (mysqli_stmt_execute($stmt)) {
            // Redirigir al index después del registro exitoso
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            header("Location: index.php");
            exit();
        } else {
            $error_message = "Error al registrar el usuario: " . mysqli_error($conn);
        }
        
        mysqli_stmt_close($stmt);
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

        .active-eye {
            color: #007bff !important; /* Azul Bootstrap cuando está activo */
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body>

    <div class="wrapper">
        <div class="header">
            <h1>La Gran Biblioteca</h1>
        </div>

        <div class="content">
            <h2>Registro de Usuario</h2>
            
            <?php if (!empty($error_message)): ?>
                <div style="background-color: #f8d7da; color: #721c24; padding: 12px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                    <strong>Error:</strong> <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" id="registerForm"> <!--- Formulario de registro -->
                
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>
                
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                
                <label for="contraseña">Contraseña:</label>
                <div style="position: relative; margin-bottom: 15px;">
                    <input type="password" id="contraseña" name="contraseña" required style="padding-right: 40px; width: 100%;">
                    <button type="button" id="eye-btn" tabindex="-1"
                        style="position: absolute; right: 8px; top: 38%; transform: translateY(-50%);
                               background: transparent; border: none; outline: none;
                               width: 24px; height: 24px; line-height: 1; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-eye" id="eye-icon" style="font-size: 1.2em; color: #888; transition: color 0.2s; display: block; margin: 0;"></i>
                    </button>
                </div>
                
                <!-- Campo para verificar contraseña -->
                <label for="verificar_contraseña">Verificar Contraseña:</label>
                <div style="position: relative; margin-bottom: 15px;">
                    <input type="password" id="verificar_contraseña" name="verificar_contraseña" required style="padding-right: 40px; width: 100%;">
                    <button type="button" id="eye-btn-verify" tabindex="-1"
                        style="position: absolute; right: 8px; top: 38%; transform: translateY(-50%);
                               background: transparent; border: none; outline: none;
                               width: 24px; height: 24px; line-height: 1; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-eye" id="eye-icon-verify" style="font-size: 1.2em; color: #888; transition: color 0.2s; display: block; margin: 0;"></i>
                    </button>
                </div>
                
                <div id="password-error" style="color:#E74C3C; font-size:1em; margin-bottom:10px; display:none;"></div>
                
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
    <script>
        // Funcionalidad para mostrar/ocultar contraseña
        document.addEventListener('DOMContentLoaded', function() {
            // Código para el primer campo de contraseña
            var eyeBtn = document.getElementById('eye-btn');
            var eyeIcon = document.getElementById('eye-icon');
            var passwordInput = document.getElementById('contraseña');
            
            eyeBtn.addEventListener('click', function() {
                // Toggle la clase para cambiar el color
                eyeIcon.classList.toggle('active-eye');
                
                // Toggle el tipo de input entre password y text
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    eyeIcon.classList.replace('bi-eye', 'bi-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    eyeIcon.classList.replace('bi-eye-slash', 'bi-eye');
                }
            });
            
            // Código para el campo de verificación de contraseña
            var eyeBtnVerify = document.getElementById('eye-btn-verify');
            var eyeIconVerify = document.getElementById('eye-icon-verify');
            var verifyPasswordInput = document.getElementById('verificar_contraseña');
            
            eyeBtnVerify.addEventListener('click', function() {
                // Toggle la clase para cambiar el color
                eyeIconVerify.classList.toggle('active-eye');
                
                // Toggle el tipo de input entre password y text
                if (verifyPasswordInput.type === 'password') {
                    verifyPasswordInput.type = 'text';
                    eyeIconVerify.classList.replace('bi-eye', 'bi-eye-slash');
                } else {
                    verifyPasswordInput.type = 'password';
                    eyeIconVerify.classList.replace('bi-eye-slash', 'bi-eye');
                }
            });
        });
        
        // Validación de contraseñas iguales antes de enviar el formulario
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            var password = document.getElementById('contraseña').value;
            var verifyPassword = document.getElementById('verificar_contraseña').value;
            var errorDiv = document.getElementById('password-error');
            if (password !== verifyPassword) {
                errorDiv.textContent = "Las contraseñas no coinciden.";
                errorDiv.style.display = "block";
                e.preventDefault();
            } else {
                errorDiv.style.display = "none";
            }
        });
    </script>
</body>
</html>
