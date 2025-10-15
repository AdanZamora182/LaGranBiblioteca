<?php

/*
[LUIS RAMOS]: Este código implementa una página segura para que los usuarios registrados agreguen reseñas de libros.
Primero verifica que el usuario esté autenticado mediante sesiones; de lo contrario, lo redirige al inicio de sesión. 
Permite al usuario buscar libros a través de la API de Google Books y seleccionar uno, asociándolo a su reseña. 
El formulario recoge la calificación, comentario, y los datos del libro, 
y luego almacena la reseña en nuestra base de datos utilizando sentencias preparadas para evitar inyecciones SQL. 
También incluye estilos modernos y mensajes de éxito dinámicos para mejorar la experiencia del usuario.
*/

session_start();
include 'config.php';

// Verifica si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Obtener el ID y el correo electrónico del usuario desde la sesión
$id_usuario = $_SESSION['user_id'];
$correo_electronico = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : ''; // Verifica si el correo electrónico está definido

$success_message = '';

// Manejo de formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $calificacion = $_POST["calificacion"];
    $comentario = $_POST["comentario"];
    $id_libro = $_POST["id_libro"];
    $nombre_libro = $_POST["nombre_libro"];

    // Nos aseguramos de que el ID del usuario y del libro están disponibles
    if (empty($id_usuario) || empty($id_libro) || empty($nombre_libro)) {
        echo "Error: Usuario, libro o nombre no especificado.";
        exit();
    }

    // Insertamos la reseña en la base de datos utilizando sentencias preparadas
    $stmt = $conn->prepare("INSERT INTO reseñas (correo_electronico, calificacion, comentario, id_usuario, id_libro, nombre_libro) VALUES (?, ?, ?, ?, ?, ?)"); 
    $stmt->bind_param("sisiss", $correo_electronico, $calificacion, $comentario, $id_usuario, $id_libro, $nombre_libro);

    // Ejecutamos la sentencia preparada
    if ($stmt->execute()) {
        $success_message = "Reseña agregada exitosamente.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Reseña</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Estilos básicos */
        @import url('https://fonts.googleapis.com/css2?family=Raleway:wght@400;700&display=swap'); /* Importar la fuente de Google Fonts */
        body { background: linear-gradient(120deg, #f0f2f5 40%, #ffffff 100%); font-family: 'Raleway', sans-serif; margin: 0; padding: 0; color: #333; }
        .container { width: 80%; max-width: 600px; margin: 50px auto; padding: 30px; background-color: rgba(255, 255, 255, 0.8); border-radius: 10px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); }
        header { text-align: center; margin-bottom: 30px; }
        header h1 { margin: 0; color: #2C3E50; font-size: 2.5em; border-bottom: 2px solid #2C3E50; padding-bottom: 10px; }
        .form-section { text-align: center; }
        label { font-weight: bold; margin-bottom: 10px; display: block; }
        input, textarea { width: calc(100% - 22px); margin-bottom: 20px; background-color: rgba(255, 255, 255, 0.2); border: 1px solid #2C3E50; border-radius: 5px; padding: 10px; font-size: 16px; color: #333; outline: none; transition: border-color 0.3s; }
        input:focus, textarea:focus { border-color: #3498DB; }
        input[type="submit"] { background-color: #2C3E50; color: #fff; border: none; border-radius: 5px; padding: 12px 20px; font-size: 16px; cursor: pointer; transition: background-color 0.3s ease; }
        input[type="submit"]:hover { background-color: #1A5276; }
        .libros-container { margin-top: 20px; }
        
        /* Estilos para los botones de navegación */
        .back-to-home-btn, .back-to-reviews-btn {
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
        
        .back-to-home-btn {
            left: 20px;
        }
        
        .back-to-reviews-btn {
            left: 70px; 
        }
        
        .back-to-home-btn:hover, .back-to-reviews-btn:hover {
            background-color: #e67e22 !important;
            transform: scale(1.1);
            color: #fff;
            text-decoration: none; /* Quitar subrayado */
        }
        
        .back-to-home-btn i, .back-to-reviews-btn i {
            font-size: 20px;
        }
        
        /* Estilos para el autocompletado */
        .autocomplete-container {
            position: relative;
            width: 100%;
        }
        
        .autocomplete-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #2C3E50;
            border-top: none;
            border-radius: 0 0 5px 5px;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
        }
        
        .autocomplete-item {
            padding: 10px;
            cursor: pointer;
            border-bottom: 1px solid #eee;
            transition: background-color 0.2s;
        }
        
        .autocomplete-item:hover {
            background-color: #f8f9fa;
        }
        
        .autocomplete-item:last-child {
            border-bottom: none;
        }
        
        .autocomplete-item.selected {
            background-color: #3498DB;
            color: white;
        }
        
        #titulo {
            border-radius: 5px 5px 0 0;
        }
        
        .autocomplete-container.open #titulo {
            border-radius: 5px 5px 0 0;
        }
    </style>
</head>
<body>
    <a href="home.php" class="back-to-home-btn">
        <i class="fas fa-home"></i>
    </a>
    <a href="reviews.php" class="back-to-reviews-btn">
        <i class="fas fa-arrow-left"></i>
    </a>
    <div class="container">
        <header>
            <h1>Agregar Reseña</h1>
        </header>
        
        <section class="form-section">
            <?php if ($success_message): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert"> <!--Mensaje de éxito -->
                    <?php echo $success_message; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <!-- Botón para cerrar el mensaje --> 
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>
            
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST"> <!-- Formulario para agregar reseñas --> 
                <label for="titulo">Buscar Libro:</label>
                <div class="autocomplete-container">
                    <input type="text" id="titulo" name="titulo" placeholder="Escribe el título del libro..." required>
                    <div class="autocomplete-dropdown" id="autocomplete-dropdown"></div>
                </div>

                <input type="hidden" id="id_libro" name="id_libro" required> 
                <input type="hidden" id="nombre_libro" name="nombre_libro" required> 
                
                <label for="calificacion">Calificación (1-5):</label>
                <input type="number" id="calificacion" name="calificacion" min="1" max="5" required> 
                
                <label for="comentario">Comentario:</label>
                <textarea id="comentario" name="comentario" rows="5" required></textarea> 
                
                <input type="submit" value="Agregar Reseña"> 
            </form>
        </section>
    </div>

    <script>
        let currentBooks = [];
        let selectedIndex = -1;
        let searchTimeout;
        
        const tituloInput = document.getElementById('titulo');
        const dropdown = document.getElementById('autocomplete-dropdown');
        const autocompleteContainer = document.querySelector('.autocomplete-container');
        
        // Función para buscar libros con autocompletado
        function buscarLibros(query) {
            if (!query || query.length < 2) {
                hideDropdown();
                return;
            }
            
            fetch(`search_books.php?autocomplete=1&query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(books => {
                    currentBooks = books;
                    showDropdown(books);
                })
                .catch(error => {
                    console.error('Error al buscar libros:', error);
                    hideDropdown();
                });
        }
        
        // Mostrar dropdown con resultados
        function showDropdown(books) {
            dropdown.innerHTML = '';
            
            if (books.length === 0) {
                dropdown.innerHTML = '<div class="autocomplete-item">No se encontraron libros</div>';
            } else {
                books.forEach((book, index) => {
                    const item = document.createElement('div');
                    item.className = 'autocomplete-item';
                    item.textContent = book.display;
                    item.addEventListener('click', () => selectBook(book));
                    dropdown.appendChild(item);
                });
            }
            
            dropdown.style.display = 'block';
            autocompleteContainer.classList.add('open');
            selectedIndex = -1;
        }
        
        // Ocultar dropdown
        function hideDropdown() {
            dropdown.style.display = 'none';
            autocompleteContainer.classList.remove('open');
            selectedIndex = -1;
        }
        
        // Seleccionar un libro
        function selectBook(book) {
            document.getElementById('titulo').value = book.display;
            document.getElementById('id_libro').value = book.id;
            document.getElementById('nombre_libro').value = book.title;
            hideDropdown();
        }
        
        // Event listeners
        tituloInput.addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                buscarLibros(e.target.value);
            }, 300);
        });
        
        tituloInput.addEventListener('keydown', function(e) {
            const items = dropdown.querySelectorAll('.autocomplete-item');
            
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                selectedIndex = Math.min(selectedIndex + 1, items.length - 1);
                updateSelection(items);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                selectedIndex = Math.max(selectedIndex - 1, -1);
                updateSelection(items);
            } else if (e.key === 'Enter') {
                e.preventDefault();
                if (selectedIndex >= 0 && currentBooks[selectedIndex]) {
                    selectBook(currentBooks[selectedIndex]);
                }
            } else if (e.key === 'Escape') {
                hideDropdown();
            }
        });
        
        // Actualizar selección visual
        function updateSelection(items) {
            items.forEach((item, index) => {
                item.classList.toggle('selected', index === selectedIndex);
            });
        }
        
        // Cerrar dropdown al hacer clic fuera
        document.addEventListener('click', function(e) {
            if (!autocompleteContainer.contains(e.target)) {
                hideDropdown();
            }
        });
        
        // Validar formulario antes de enviar
        document.querySelector('form').addEventListener('submit', function(e) {
            const idLibro = document.getElementById('id_libro').value;
            if (!idLibro) {
                e.preventDefault();
                alert('Por favor selecciona un libro de la lista');
                tituloInput.focus();
            }
        });

        // Ocultar el mensaje de éxito después de 3 segundos
        $(document).ready(function() {
            setTimeout(function() {
                $('.alert-success').alert('close');
            }, 3000);
        });
    </script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>