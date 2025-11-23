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
        // Verificar si el correo ya existe en la base de datos
        $stmt_check = mysqli_prepare($conn, "SELECT id FROM usuarios WHERE email = ?");
        mysqli_stmt_bind_param($stmt_check, "s", $email);
        mysqli_stmt_execute($stmt_check);
        mysqli_stmt_store_result($stmt_check);
        
        if (mysqli_stmt_num_rows($stmt_check) > 0) {
            // El correo ya está registrado
            $error_message = "Este correo electrónico ya está registrado. Por favor, utiliza otro correo o inicia sesión.";
            mysqli_stmt_close($stmt_check);
        } else {
            mysqli_stmt_close($stmt_check);
            
            // Usar prepared statements para evitar SQL injection
            $stmt = mysqli_prepare($conn, "INSERT INTO usuarios (nombre, email, contraseña) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "sss", $nombre, $email, $contraseña);
            
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
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario - La Gran Biblioteca</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #2C3E50 0%, #E74C3C 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar-custom {
            background: #2C3E50;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white !important;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .navbar-brand img {
            height: 40px;
            border-radius: 8px;
        }

        .main-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .register-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            max-width: 500px;
            width: 100%;
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card-header-custom {
            background: linear-gradient(135deg, #2C3E50 0%, #E74C3C 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .card-header-custom i {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .card-header-custom h2 {
            font-weight: 600;
            margin: 0;
            font-size: 1.8rem;
        }

        .card-body-custom {
            padding: 2.5rem;
        }

        .form-label {
            font-weight: 500;
            color: #495057;
            margin-bottom: 0.5rem;
        }

        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #E74C3C;
            box-shadow: 0 0 0 0.2rem rgba(231, 76, 60, 0.25);
        }

        .input-group {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6c757d;
            cursor: pointer;
            z-index: 10;
            padding: 0;
            font-size: 1.2rem;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: #E74C3C;
        }

        .password-toggle.active {
            color: #E74C3C;
        }

        .btn-register {
            background: linear-gradient(135deg, #E74C3C 0%, #C0392B 100%);
            border: none;
            border-radius: 10px;
            color: white;
            font-weight: 600;
            padding: 0.75rem;
            font-size: 1.1rem;
            width: 100%;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(231, 76, 60, 0.4);
            background: linear-gradient(135deg, #C0392B 0%, #E74C3C 100%);
        }

        .btn-back {
            background: white;
            border: 2px solid #2C3E50;
            border-radius: 10px;
            color: #2C3E50;
            font-weight: 600;
            padding: 0.75rem;
            font-size: 1rem;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background: #2C3E50;
            color: white;
            transform: translateY(-2px);
        }

        .divider {
            text-align: center;
            margin: 1.5rem 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e0e0e0;
        }

        .divider span {
            background: white;
            padding: 0 1rem;
            position: relative;
            color: #6c757d;
            font-size: 0.9rem;
        }

        .password-strength {
            font-size: 0.85rem;
            margin-top: 0.25rem;
            display: none;
        }

        .password-strength.weak {
            color: #dc3545;
        }

        .password-strength.medium {
            color: #ffc107;
        }

        .password-strength.strong {
            color: #28a745;
        }

        .footer-custom {
            background: #2C3E50;
            color: white;
            text-align: center;
            padding: 1.5rem;
            margin-top: auto;
        }

        .footer-custom p {
            margin: 0;
            font-size: 0.9rem;
        }

        @media (max-width: 576px) {
            .card-body-custom {
                padding: 1.5rem;
            }

            .register-card {
                margin: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-custom navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="Logo.jpg" alt="Logo">
                La Gran Biblioteca
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="register-card">
            <!-- Card Header -->
            <div class="card-header-custom">
                <i class="bi bi-person-plus-fill"></i>
                <h2>Registro de Usuario</h2>
                <p class="mb-0" style="opacity: 0.9;">Crea tu cuenta y comienza a explorar</p>
            </div>

            <!-- Card Body -->
            <div class="card-body-custom">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" id="registerForm">
                    <!-- Name Input -->
                    <div class="mb-3">
                        <label for="nombre" class="form-label">
                            <i class="bi bi-person me-2"></i>Nombre Completo
                        </label>
                        <input type="text" class="form-control" id="nombre" name="nombre"
                               placeholder="Tu nombre completo" required>
                    </div>

                    <!-- Email Input -->
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope me-2"></i>Correo Electrónico
                        </label>
                        <input type="email" class="form-control" id="email" name="email"
                               placeholder="ejemplo@correo.com" required>
                    </div>

                    <!-- Password Input -->
                    <div class="mb-3">
                        <label for="contraseña" class="form-label">
                            <i class="bi bi-lock me-2"></i>Contraseña
                        </label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="contraseña" name="contraseña"
                                   placeholder="Crea una contraseña segura" required>
                            <button type="button" class="password-toggle" id="togglePassword">
                                <i class="bi bi-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                        <div id="passwordStrength" class="password-strength"></div>
                    </div>

                    <!-- Confirm Password Input -->
                    <div class="mb-4">
                        <label for="verificar_contraseña" class="form-label">
                            <i class="bi bi-lock-fill me-2"></i>Confirmar Contraseña
                        </label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="verificar_contraseña" name="verificar_contraseña"
                                   placeholder="Confirma tu contraseña" required>
                            <button type="button" class="password-toggle" id="togglePasswordVerify">
                                <i class="bi bi-eye" id="eyeIconVerify"></i>
                            </button>
                        </div>
                        <div id="passwordMatch" class="password-strength"></div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-register mb-3">
                        <i class="bi bi-person-check me-2"></i>Crear Cuenta
                    </button>
                </form>

                <!-- Divider -->
                <div class="divider">
                    <span>o</span>
                </div>

                <!-- Back Button -->
                <a href="index.php" class="btn btn-back">
                    <i class="bi bi-arrow-left me-2"></i>Regresar al Inicio
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer-custom">
        <p>&copy; 2024 La Gran Biblioteca. Todos los derechos reservados.</p>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Toaster JS -->
    <script src="toaster.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle password visibility for password field
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('contraseña');
            const eyeIcon = document.getElementById('eyeIcon');

            togglePassword.addEventListener('click', function() {
                const type = passwordInput.type === 'password' ? 'text' : 'password';
                passwordInput.type = type;
                eyeIcon.classList.toggle('bi-eye');
                eyeIcon.classList.toggle('bi-eye-slash');
                this.classList.toggle('active');
            });

            // Toggle password visibility for verify password field
            const togglePasswordVerify = document.getElementById('togglePasswordVerify');
            const verifyPasswordInput = document.getElementById('verificar_contraseña');
            const eyeIconVerify = document.getElementById('eyeIconVerify');

            togglePasswordVerify.addEventListener('click', function() {
                const type = verifyPasswordInput.type === 'password' ? 'text' : 'password';
                verifyPasswordInput.type = type;
                eyeIconVerify.classList.toggle('bi-eye');
                eyeIconVerify.classList.toggle('bi-eye-slash');
                this.classList.toggle('active');
            });

            // Password strength indicator
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                const strengthIndicator = document.getElementById('passwordStrength');

                if (password.length === 0) {
                    strengthIndicator.style.display = 'none';
                    return;
                }

                strengthIndicator.style.display = 'block';
                let strength = 0;

                if (password.length >= 8) strength++;
                if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
                if (/\d/.test(password)) strength++;
                if (/[^a-zA-Z\d]/.test(password)) strength++;

                strengthIndicator.className = 'password-strength';
                if (strength <= 1) {
                    strengthIndicator.classList.add('weak');
                    strengthIndicator.textContent = '⚠ Contraseña débil';
                } else if (strength === 2 || strength === 3) {
                    strengthIndicator.classList.add('medium');
                    strengthIndicator.textContent = '✓ Contraseña aceptable';
                } else {
                    strengthIndicator.classList.add('strong');
                    strengthIndicator.textContent = '✓ Contraseña fuerte';
                }
            });

            // Password match indicator
            verifyPasswordInput.addEventListener('input', function() {
                const password = passwordInput.value;
                const verifyPassword = this.value;
                const matchIndicator = document.getElementById('passwordMatch');

                if (verifyPassword.length === 0) {
                    matchIndicator.style.display = 'none';
                    return;
                }

                matchIndicator.style.display = 'block';
                matchIndicator.className = 'password-strength';

                if (password === verifyPassword) {
                    matchIndicator.classList.add('strong');
                    matchIndicator.textContent = '✓ Las contraseñas coinciden';
                } else {
                    matchIndicator.classList.add('weak');
                    matchIndicator.textContent = '✗ Las contraseñas no coinciden';
                }
            });

            // Form validation
            document.getElementById('registerForm').addEventListener('submit', function(e) {
                const password = passwordInput.value;
                const verifyPassword = verifyPasswordInput.value;

                if (password !== verifyPassword) {
                    e.preventDefault();
                    showErrorToast('Las contraseñas no coinciden. Por favor, verifica que ambas contraseñas sean iguales.');
                    return false;
                }

                if (password.length < 6) {
                    e.preventDefault();
                    showWarningToast('La contraseña debe tener al menos 6 caracteres.');
                    return false;
                }
            });

            // Show error toast if there's an error
            <?php if (!empty($error_message)): ?>
                showErrorToast('<?php echo addslashes($error_message); ?>');
            <?php endif; ?>
        });
    </script>
</body>
</html>
