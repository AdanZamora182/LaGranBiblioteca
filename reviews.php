<?php
// [LUIS RAMOS]: Página que te permite seleccionar si agregar reseñas o ver mis reseñas

session_start();
// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?error=not_logged_in");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reseñas</title>
    <link rel="icon" href="iconolibros.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Estilos básicos */
        @import url('https://fonts.googleapis.com/css2?family=Raleway:wght@400;700&display=swap');
        body {
            font-family: 'Raleway', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(120deg, #f0f2f5 40%, #ffffff 100%);
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Estilos para el contenedor */
        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            text-align: center;
        }

        h1 {
            color: #2C3E50;
        }

        .options {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }

        .options a {
            text-decoration: none;
            color: #ecf0f1;
            padding: 10px 20px;
            border-radius: 25px;
            background: #E74C3C;
            transition: background 0.3s ease;
        }

        .options a:hover {
            background: #C0392B;
        }

        /* Estilos para el botón de regreso */
        .back-home-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            color: #fff;
            background-color: #ff8c00;
            padding: 10px;
            border-radius: 50%;
            text-decoration: none;
            border: none;
            transition: background-color 0.3s ease, transform 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .back-home-btn:hover {
            background-color: #e67e22;
            transform: scale(1.1);
        }

        .back-home-btn i {
            font-size: 20px;
        }

        .book-animation {
            margin-top: 20px;
        }

        .book-animation img {
            width: 150px; /* Ajusta el tamaño del GIF */
            height: auto;
        }
    </style>
</head>
<body>
    <a href="home.php" class="back-home-btn">
        <i class="fas fa-home"></i>
    </a>
    <div class="container">
        <h1>Reseñas</h1>
        <div class="options">
            <a href="add_review.php">Agregar Reseña</a>
            <a href="my_reviews.php">Ver Mis Reseñas</a>
        </div>
        <div class="book-animation">
            <img src="book_animation.gif" alt="Libro pasando páginas">
        </div>
    </div>
</body>
</html>