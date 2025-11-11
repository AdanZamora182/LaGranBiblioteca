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
    <title>Iniciar Sesión - La Gran Biblioteca</title>

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

        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            max-width: 450px;
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

        .btn-login {
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

        .btn-login:hover {
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

            .login-card {
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
        <div class="login-card">
            <!-- Card Header -->
            <div class="card-header-custom">
                <i class="bi bi-person-circle"></i>
                <h2>Iniciar Sesión</h2>
                <p class="mb-0" style="opacity: 0.9;">Bienvenido de vuelta</p>
            </div>

            <!-- Card Body -->
            <div class="card-body-custom">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" id="loginForm">
                    <!-- Email Input -->
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope me-2"></i>Correo Electrónico
                        </label>
                        <input type="email" class="form-control" id="email" name="email"
                               placeholder="ejemplo@correo.com" required>
                    </div>

                    <!-- Password Input -->
                    <div class="mb-4">
                        <label for="contraseña" class="form-label">
                            <i class="bi bi-lock me-2"></i>Contraseña
                        </label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="contraseña" name="contraseña"
                                   placeholder="Ingresa tu contraseña" required>
                            <button type="button" class="password-toggle" id="togglePassword">
                                <i class="bi bi-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-login mb-3">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
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
        // Toggle password visibility
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('contraseña');
            const eyeIcon = document.getElementById('eyeIcon');

            togglePassword.addEventListener('click', function() {
                // Toggle password visibility
                const type = passwordInput.type === 'password' ? 'text' : 'password';
                passwordInput.type = type;

                // Toggle icon
                eyeIcon.classList.toggle('bi-eye');
                eyeIcon.classList.toggle('bi-eye-slash');

                // Toggle active class
                this.classList.toggle('active');
            });

            // Show error toast if there's an error
            <?php if (isset($error)): ?>
                showErrorToast('<?php echo addslashes($error); ?>');
            <?php endif; ?>
        });
    </script>
</body>
</html>