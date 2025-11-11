<?php
// [TRISTAN EGUIA]: Código para editar una reseña

session_start();
include 'config.php';

// Verifica si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id']; // Obtener el ID de la reseña a editar

// Obtener la reseña actual de la base de datos
$sql = "SELECT * FROM reseñas WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Verificar si la reseña existe
if ($result->num_rows == 0) {
    echo "Reseña no encontrada.";
    exit();
}

$row = $result->fetch_assoc(); // Obtener los datos de la reseña

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $calificacion = $_POST['calificacion'];
    $comentario = $_POST['comentario'];

    // Actualiza la reseña en la base de datos
    $sql = "UPDATE reseñas SET calificacion = ?, comentario = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isi", $calificacion, $comentario, $id);

    if ($stmt->execute()) {
        $stmt->close();
        mysqli_close($conn);
        header("Location: my_reviews.php"); // Redirigir a la lista de reseñas
        exit();
    } else {
        echo "Error al actualizar la reseña: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Reseña</title>
    <link rel="icon" href="iconolibros.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Estilos básicos */
        @import url('https://fonts.googleapis.com/css2?family=Raleway:wght@400;700&display=swap');
        body {
            font-family: 'Raleway', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(120deg, #f0f2f5 40%, #ffffff 100%);
            color: #333;
        }

        /* Estilos del formulario */
        .container {
            width: 50%;
            margin: 50px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h1 {
            text-align: center;
            color: #2C3E50;
        }

        label {
            display: block;
            margin: 15px 0 5px;
            font-weight: bold;
        }

        input[type="number"], textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            background-color: #2980B9;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
            width: 100%;
        }

        button:hover {
            background-color: #1A5276;
        }

        /* Estilos de los botones de navegación */
        .back-home-btn, .back-to-reviews-btn {
            position: absolute;
            top: 20px;
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
            text-decoration: none; /* Quitar subrayado */
        }

        .back-home-btn {
            left: 20px;
        }

        .back-to-reviews-btn {
            left: 70px; 
        }

        .back-home-btn:hover, .back-to-reviews-btn:hover {
            background-color: #e67e22 !important;
            transform: scale(1.1);
            color: #fff;
            text-decoration: none; /* Quitar subrayado */
        }

        .back-home-btn i, .back-to-reviews-btn i {
            font-size: 20px;
        }
    </style>
</head>
<body>
    <a href="home.php" class="back-home-btn">
        <i class="fas fa-home"></i>
    </a>
    <a href="my_reviews.php" class="back-to-reviews-btn">
        <i class="fas fa-arrow-left"></i>
    </a>
    <div class="container">
        <h1>Editar Reseña</h1>
        <form method="POST" action="">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
            <label for="calificacion">Calificación:</label>
            <input type="number" name="calificacion" id="calificacion" value="<?php echo htmlspecialchars($row['calificacion']); ?>" min="1" max="5" required> <!-- Mostrar la calificación actual -->
            
            <label for="comentario">Comentario:</label>
            <textarea name="comentario" id="comentario" rows="5" required><?php echo htmlspecialchars($row['comentario']); ?></textarea> <!-- Mostrar el comentario actual -->
            
            <button type="submit">Actualizar Reseña</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>