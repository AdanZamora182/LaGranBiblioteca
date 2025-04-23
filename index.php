<!-- 
 * [LUIS RAMOS]: Este es el código de nuestra primera página, incluimos una API de frases y las opciones para registrarse o iniciar sesión.
-->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La Gran Biblioteca</title>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Raleway:wght@400;700&display=swap'); /* Fuente de Google Fonts */
        
        /* Estilos generales */
        body {
            font-family: 'Raleway', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(120deg, #f0f2f5 40%, #ffffff 100%);
            color: #333;
        }

        /* Estilos para el encabezado, navegación, contenido y pie de página */
        .header {
            background-color: #2C3E50;
            color: white;
            padding: 20px 0;
            text-align: center;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .header img {
            height: 50px;
        }

        .header h1 {
            font-size: 2.5em;
            margin: 0;
        }

        .header p {
            margin: 5px 0;
            font-size: 1.2em;
            color: #BDC3C7;
        }

        /* Estilos para la navegación */
        .nav {
            margin: 20px 0;
            text-align: center;
        }

        .nav a {
            margin: 0 15px;
            text-decoration: none;
            color: #ecf0f1;
            font-size: 1.1em;
            padding: 10px 20px;
            border-radius: 25px;
            background: #E74C3C;
            transition: background 0.3s ease, transform 0.3s ease;
        }

        .nav a:hover {
            background: #C0392B;
            transform: scale(1.05);
        }

        /* Estilos para el contenido */
        .content {
            padding: 20px 20px;
        }

        .section {
            margin: 20px auto;
            padding: 40px;
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .section:hover {
            transform: translateY(-10px);
        }

        .section h2 {
            font-size: 2em;
            margin-bottom: 20px;
            color: #2C3E50;
        }

        .section p {
            font-size: 1.1em;
            line-height: 1.8;
            color: #7F8C8D;
        }

        .section img {
            max-width: 80%;
            border-radius: 16px;
            margin: 20px 0;
            transition: transform 0.3s ease;
        }

        .section img:hover {
            transform: scale(1.05);
        }

        /* Estilos para el pie de página */
        .footer {
            background-color: #2C3E50;
            color: #ecf0f1;
            padding: 20px 0;
            text-align: center;
        }

        .footer p {
            margin: 0;
        }

        .footer .social-icons {
            margin-top: 10px;
        }

        .footer .social-icons a {
            margin: 0 10px;
            color: #ecf0f1;
            font-size: 1.5em;
            transition: color 0.3s ease;
        }

        .footer .social-icons a:hover {
            color: #E74C3C;
        }

        /* Estilos para la sección de la frase */
        .quote-section {
            margin: 20px auto;
            padding: 30px;
            background-color: #f9f9f9;
            border-radius: 12px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            text-align: center;
        }

        .quote-section h3 {
            font-size: 1.8em;
            margin-bottom: 20px;
            color: #2980B9;
        }

        .quote-section p {
            font-size: 1.4em;
            color: #34495E;
            font-style: italic;
        }
    </style>
    
    <script>
        // Cargar una frase al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            fetch('https://ron-swanson-quotes.herokuapp.com/v2/quotes') // Obtener una frase
                .then(response => response.json()) // Convertir la respuesta a JSON
                .then(data => { 
                    document.getElementById('quote').textContent = `"${data[0]}"`; // Mostrar la frase en la página
                })
                .catch(error => { // Manejar errores
                    console.error('Error fetching the quote:', error); 
                    document.getElementById('quote').textContent = 'No se pudo cargar la frase. Inténtalo más tarde.';
                });
        });
    </script>
    
</head>
<body>
        <!-- Encabezado de la página -->
    <div class="header">
        <img src="Logo.jpg" alt="Logo">
        <h1>La Gran Biblioteca</h1>
        <p>¡Lee, explora y descubre!</p>
        <div class="nav">
            <a href="login.php">Iniciar Sesión</a>
            <a href="register.php">Registro</a>
        </div>
    </div>

    <div class="content">
        <!-- Frase -->
        <div class="quote-section" id="quote-section">
            <h3>Frase para reflexionar</h3>
            <p id="quote">Cargando frase...</p>
        </div>
        <!-- Para iniciar sesión -->
        <div class="section" id="login">
            <h2>Iniciar Sesión</h2>
            <p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a>.</p>
        </div>

        <!-- Para registrarse -->
        <div class="section" id="register">
            <h2>Registro de Usuario</h2>
            <p>¿No tienes una cuenta? <a href="register.php">Regístrate ahora</a>.</p>
        </div>
    </div>
        <!-- Pie de página -->
    <div class="footer">
        <p>&copy; 2024 La Gran Biblioteca. Todos los derechos reservados.</p>
        <div class="social-icons">
            <a href="https://facebook.com" target="_blank">Facebook</a>
            <a href="https://twitter.com" target="_blank">X</a>
            <a href="https://instagram.com" target="_blank">Instagram</a>
        </div>
    </div>

</body>
</html>


