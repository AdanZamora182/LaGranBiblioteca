<!--
 * [LUIS RAMOS]: Este es el código de nuestra primera página, incluimos una API de frases y las opciones para registrarse o iniciar sesión.
-->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La Gran Biblioteca</title>

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
            background: linear-gradient(120deg, #f0f2f5 40%, #ffffff 100%);
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header / Navbar */
        .header-custom {
            background: #2C3E50;
            color: white;
            padding: 1.5rem 0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        /* Asegurar que el contenido del header esté centrado independientemente de Bootstrap */
        .header-custom .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 1rem;
        }

        .header-custom .logo-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.5rem; /* Reducir espacio entre elementos del logo */
        }

        .header-custom .logo-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 0; /* Remover margen para usar gap del contenedor */
        }

        /* Contenedor del logo sin fondo */
        .logo-wrapper {
            background: transparent;
            padding: 0.15rem; /* pequeño espaciado opcional */
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* Imagen del logo sin fondo ni sombra para que el contenedor sea transparente */
        .header-custom img {
            height: 60px;
            border-radius: 12px;
            box-shadow: none;
            background: transparent;
            display: block;
        }

        .header-custom h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0;
        }

        .header-custom .tagline {
            font-size: 1.1rem;
            color: #BDC3C7;
            margin: 0; /* Remover márgenes para centrar con flexbox */
            text-align: center;
            width: 100%;
        }

        .nav-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .nav-buttons .btn-custom {
            background: linear-gradient(135deg, #E74C3C 0%, #C0392B 100%);
            border: none;
            border-radius: 25px;
            color: white;
            font-weight: 600;
            padding: 0.75rem 2rem;
            font-size: 1.1rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-buttons .btn-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(231, 76, 60, 0.4);
            background: linear-gradient(135deg, #C0392B 0%, #E74C3C 100%);
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 3rem 1rem;
        }

        .quote-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            margin: 0 auto 3rem;
            max-width: 800px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            text-align: center;
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .quote-card h3 {
            font-size: 1.8rem;
            color: #E74C3C;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }

        .quote-card p {
            font-size: 1.3rem;
            color: #34495E;
            font-style: italic;
            line-height: 1.8;
        }

        .info-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            margin: 0 auto 2rem;
            max-width: 900px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.15);
        }

        .info-card h2 {
            font-size: 2rem;
            color: #2C3E50;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .info-card p {
            font-size: 1.1rem;
            color: #7F8C8D;
            line-height: 1.8;
        }

        .info-card a {
            color: #E74C3C;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .info-card a:hover {
            color: #C0392B;
            text-decoration: underline;
        }

        .info-card .btn-action {
            background: linear-gradient(135deg, #2C3E50 0%, #34495E 100%);
            border: none;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            padding: 0.75rem 2rem;
            font-size: 1.1rem;
            margin-top: 1rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-card .btn-action:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(44, 62, 80, 0.4);
            background: linear-gradient(135deg, #34495E 0%, #2C3E50 100%);
        }

        /* Footer */
        .footer-custom {
            background: #2C3E50;
            color: white;
            padding: 2rem 0;
            text-align: center;
            margin-top: auto;
        }

        .footer-custom p {
            margin: 0 0 1rem 0;
            font-size: 0.95rem;
        }

        .social-links {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .social-links a {
            color: #ecf0f1;
            font-size: 1rem;
            text-decoration: none;
            transition: color 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .social-links a:hover {
            color: #E74C3C;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header-custom h1 {
                font-size: 2rem;
            }

            .quote-card,
            .info-card {
                padding: 1.5rem;
                margin-bottom: 1.5rem;
            }

            .nav-buttons .btn-custom {
                padding: 0.6rem 1.5rem;
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header-custom">
        <div class="container">
            <div class="logo-container">
                <div class="logo-wrapper">
                    <img src="Logo.jpg" alt="Logo La Gran Biblioteca">
                </div>
                <h1>La Gran Biblioteca</h1>
                <p class="tagline">¡Lee, explora y descubre!</p>
            </div>
            <div class="nav-buttons">
                <a href="login.php" class="btn-custom">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Iniciar Sesión
                </a>
                <a href="register.php" class="btn-custom">
                    <i class="bi bi-person-plus"></i>
                    Registro
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <!-- Quote Section -->
            <div class="quote-card">
                <h3>
                    <i class="bi bi-chat-quote me-2"></i>
                    Frase para reflexionar
                </h3>
                <p id="quote">Cargando frase...</p>
            </div>

            <!-- Login Section -->
            <div class="info-card">
                <i class="bi bi-door-open" style="font-size: 3rem; color: #E74C3C; margin-bottom: 1rem;"></i>
                <h2>Iniciar Sesión</h2>
                <p>¿Ya tienes una cuenta? Inicia sesión y accede a tu biblioteca personal.</p>
                <a href="login.php" class="btn btn-action">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Inicia sesión aquí
                </a>
            </div>

            <!-- Register Section -->
            <div class="info-card">
                <i class="bi bi-person-check" style="font-size: 3rem; color: #2C3E50; margin-bottom: 1rem;"></i>
                <h2>Registro de Usuario</h2>
                <p>¿No tienes una cuenta? Regístrate ahora y comienza tu viaje literario.</p>
                <a href="register.php" class="btn btn-action">
                    <i class="bi bi-person-plus"></i>
                    Regístrate ahora
                </a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer-custom">
        <div class="container">
            <p>&copy; 2024 La Gran Biblioteca. Todos los derechos reservados.</p>
            <div class="social-links">
                <a href="https://facebook.com" target="_blank">
                    <i class="bi bi-facebook"></i> Facebook
                </a>
                <a href="https://twitter.com" target="_blank">
                    <i class="bi bi-twitter-x"></i> X
                </a>
                <a href="https://instagram.com" target="_blank">
                    <i class="bi bi-instagram"></i> Instagram
                </a>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Cargar una frase al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            fetch('https://ron-swanson-quotes.herokuapp.com/v2/quotes')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('quote').textContent = `"${data[0]}"`;
                })
                .catch(error => {
                    console.error('Error fetching the quote:', error);
                    document.getElementById('quote').textContent = 'No se pudo cargar la frase. Inténtalo más tarde.';
                });
        });
    </script>
</body>
</html>


