<?php
// [LUIS RAMOS]: Código para una vez iniciado sesión. Este codigo nos muestra los libros por categorías

session_start(); // Inicia la sesión

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    // Si no ha iniciado sesión, redirigir al index
    header("Location: index.php?error=not_logged_in");
    exit(); // Asegurar que el script se detenga después de redirigir
}

// Si el usuario ha iniciado sesión, mostrar el nombre de usuario
$username = htmlspecialchars($_SESSION['username']); // Escapar salida para evitar XSS
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - <?php echo $username; ?></title> <!-- Mostrar el nombre de usuario en el título -->
    <link rel="icon" href="iconolibros.png" type="image/png"> <!-- Favicon -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Raleway:wght@400;700&display=swap'); /* Fuente de Google Fonts */

        body {
            font-family: 'Raleway', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(120deg, #f0f2f5 40%, #ffffff 100%);
            color: #333;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.8); 
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 1px solid #ddd;
        }

        header h1 {
            margin: 0;
            color: #2C3E50;
            font-size: 2.5em;
        }

        .logout-link, .search-books-link, .add-review-link {
            margin-top: 10px;
            margin-right: 20px;
            text-decoration: none;
            color: #ecf0f1;
            padding: 10px 20px;
            border-radius: 25px;
            background: #E74C3C;
            transition: background 0.3s ease;
        }

        .logout-link:hover, .search-books-link:hover, .add-review-link:hover {
            background: #C0392B;
        }

        .book-info {
            margin-top: 40px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .book-info h2 {
            font-size: 2em;
            color: #2980B9;
            text-align: center;
        }

        .book-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            grid-gap: 20px;
        }

        .book {
            background-color: #fff;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .book:hover {
            transform: translateY(-5px);
        }

        .book h3 {
            margin: 0;
            font-size: 22px;
            color: #444;
            text-align: center;
        }

        .book p {
            margin: 10px 0;
            color: #666;
            text-align: center;
        }

        .book a {
            display: inline-block;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .book a:hover { 
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>RECOMENDACIONES DE LIBROS</h1> <!-- Título principal -->
            <p>Bienvenido, <?php echo $username; ?>.</p> <!-- Mostrar el nombre de usuario -->
            <a href="logout.php" class="logout-link">Cerrar Sesión</a> <!-- Enlace para cerrar sesión -->
            <a href="home.php" class="search-books-link">Mi Cuenta</a> <!-- Nuevo botón Mi Cuenta -->
            <a href="busquedalibros.php" class="search-books-link">Buscar Libros</a> <!-- Enlace para buscar libros -->
            <a href="reviews.php" class="add-review-link">Reseñas</a> <!-- Enlace para ver reseñas -->
            <a href="encuestaRecomendacion.php" class="search-books-link">Encuesta de Recomendación</a> <!-- Nuevo botón -->
        </header>
        
        <!-- Sección de libros por género -->
        <section class="book-info">
            <h2>Romance</h2>
            <?php 
            include 'get_books.php'; // Incluir archivo que contiene la función getBooksByGenre
            if (function_exists('getBooksByGenre')) { 
                getBooksByGenre('romance'); // Obtener libros del género romance
            } else {
                echo "<p>Error al cargar los libros.</p>";
            }
            ?>
        </section>
        <section class="book-info">
            <h2>Clásicos</h2>
            <?php 
            if (function_exists('getBooksByGenre')) {
                getBooksByGenre('classics'); // Obtener libros del género clásicos
            } else {
                echo "<p>Error al cargar los libros.</p>";
            }
            ?>
        </section>
        <section class="book-info">
            <h2>Ficción</h2>
            <?php 
            if (function_exists('getBooksByGenre')) {
                getBooksByGenre('fiction'); // Obtener libros del género ficción
            } else {
                echo "<p>Error al cargar los libros.</p>";
            }
            ?>
        </section>
        <section class="book-info">
            <h2>No Ficción</h2>
            <?php 
            if (function_exists('getBooksByGenre')) {
                getBooksByGenre('nonfiction'); // Obtener libros del género no ficción
            } else {
                echo "<p>Error al cargar los libros.</p>";
            }
            ?>
        </section>
        <section class="book-info">
            <h2>Misterio</h2>
            <?php 
            if (function_exists('getBooksByGenre')) {
                getBooksByGenre('mystery'); // Obtener libros del género misterio
            } else {
                echo "<p>Error al cargar los libros.</p>";
            }
            ?>
        </section>
        <section class="book-info">
            <h2>Ciencia Ficción</h2>
            <?php 
            if (function_exists('getBooksByGenre')) {
                getBooksByGenre('science fiction'); // Obtener libros del género ciencia ficción
            } else {
                echo "<p>Error al cargar los libros.</p>";
            }
            ?>
        </section>
        <section class="book-info">
            <h2>Fantasía</h2>
            <?php 
            if (function_exists('getBooksByGenre')) {
                getBooksByGenre('fantasy'); // Obtener libros del género fantasía
            } else {
                echo "<p>Error al cargar los libros.</p>";
            }
            ?>
        </section>
    </div>
</body>
</html>