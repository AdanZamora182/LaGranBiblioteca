<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recomendaciones de Libros</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-image: linear-gradient(135deg, #f5f7fa 0%, #e4e7eb 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }
        
        .container {
            backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.15);
            margin-top: 30px;
            margin-bottom: 30px;
        }
        
        .title-section {
            text-align: center;
            margin-bottom: 30px;
            color: #2c3e50;
        }
        
        .title-section h1 {
            font-weight: 600;
            position: relative;
            display: inline-block;
            margin-bottom: 15px;
        }
        
        .title-section h1:after {
            content: '';
            position: absolute;
            width: 50%;
            height: 3px;
            background-color: #3498db;
            bottom: -10px;
            left: 25%;
            border-radius: 3px;
        }
        
        .book-card {
            text-align: center;
            margin-bottom: 30px;
            transition: all 0.3s ease;
        }
        
        .book-card:hover {
            transform: translateY(-5px);
        }
        
        .book-cover {
            width: 180px;
            height: 250px;
            cursor: pointer;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }
        
        .book-cover:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }
        
        .book-title {
            font-size: 18px;
            font-weight: 600;
            margin-top: 15px;
            color: #2c3e50;
        }
        
        .book-author {
            font-size: 16px;
            color: #7f8c8d;
            margin-top: 5px;
        }
        
        .book-detail {
            display: none;
            padding: 25px;
            border-radius: 12px;
            background-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            margin-bottom: 30px;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }
        
        .book-detail h2 {
            color: #2c3e50;
            font-size: 26px;
            margin-bottom: 20px;
            border-bottom: 2px solid #e0e0e0;
            padding-bottom: 10px;
        }
        
        .book-detail p {
            font-size: 16px;
            margin-bottom: 15px;
            line-height: 1.6;
        }
        
        .book-detail img {
            float: right;
            margin-left: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
            max-width: 180px;
        }
        
        .book-meta {
            display: flex;
            flex-wrap: wrap;
            margin: 20px 0;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }
        
        .book-meta div {
            flex: 1 0 25%;
            padding: 10px;
            font-size: 14px;
        }
        
        .meta-label {
            font-weight: bold;
            display: block;
            color: #3498db;
            margin-bottom: 5px;
        }
        
        .close-detail {
            position: absolute;
            top: 15px;
            right: 15px;
            background-color: #e0e0e0;
            border: none;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .close-detail:hover {
            background-color: #d0d0d0;
        }
        
        .btn-navigation {
            padding: 10px 20px;
            border: none;
            border-radius: 25px;
            margin: 10px;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .btn-home {
            background-color: #3498db;
            color: white;
        }
        
        .btn-home:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }
        
        .btn-retry {
            background-color: #e74c3c;
            color: white;
        }
        
        .btn-retry:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
        }
        
        .nav-buttons {
            display: flex;
            justify-content: center;
            margin-top: 30px;
            margin-bottom: 15px;
        }
        
        .book-description {
            max-height: 150px;
            overflow: hidden;
            position: relative;
            transition: max-height 0.5s ease;
        }
        
        .book-description.expanded {
            max-height: 1000px;
        }
        
        .expand-description {
            color: #3498db;
            cursor: pointer;
            text-decoration: underline;
            margin-top: 10px;
            display: inline-block;
        }
        
        .book-categories {
            margin-top: 10px;
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }
        
        .category-tag {
            background-color: #e0f7fa;
            color: #00838f;
            padding: 3px 10px;
            border-radius: 15px;
            font-size: 12px;
            display: inline-block;
        }
        
        .compatibility-indicator {
            background-color: #e8f5e9;
            color: #2e7d32;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 14px;
            display: inline-block;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="title-section">
            <h1>Tus Recomendaciones Personalizadas</h1>
            <p>Basadas en tus preferencias de lectura</p>
        </div>
        <div class="row" id="recommendations"></div>
        <div id="book-detail" class="book-detail mt-4"></div>
        <div class="nav-buttons">
            <button class="btn-navigation btn-retry" onclick="window.location.href='encuestaRecomendacion.php'">
                <i class="fas fa-redo mr-2"></i>Nueva Recomendación
            </button>
            <button class="btn-navigation btn-home" onclick="window.location.href='home.php'">
                <i class="fas fa-home mr-2"></i>Volver al Inicio
            </button>
        </div>
    </div>
    <script>
        // Obtener recomendaciones desde el servidor
        document.addEventListener("DOMContentLoaded", async () => {
            try {
                const response = await fetch("recomendar_libro.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify(JSON.parse(localStorage.getItem("perfilUsuario")))
                });
                const data = await response.json();
                console.log("Datos recibidos:", data); // Depuración

                if (data.recomendaciones && Array.isArray(data.recomendaciones)) {
                    mostrarRecomendaciones(data.recomendaciones);
                } else if (data.error) {
                    document.getElementById("recommendations").innerHTML = `<div class="col-12 text-center"><p class="alert alert-warning">${data.error}</p></div>`;
                } else {
                    document.getElementById("recommendations").innerHTML = `<div class="col-12 text-center"><p class="alert alert-danger">Error desconocido al procesar las recomendaciones.</p></div>`;
                }
            } catch (error) {
                console.error("Error al obtener las recomendaciones:", error);
                document.getElementById("recommendations").innerHTML = `<div class="col-12 text-center"><p class="alert alert-danger">Error al conectar con el servidor.</p></div>`;
            }
        });

        // Mostrar recomendaciones en tarjetas
        async function mostrarRecomendaciones(recomendaciones) {
            const container = document.getElementById("recommendations");
            container.innerHTML = ""; // Limpiar el contenedor antes de agregar nuevas tarjetas

            for (const libro of recomendaciones) {
                console.log("Procesando libro:", libro); // Depuración
                if (!libro.titulo) {
                    console.warn("El libro no tiene un título definido:", libro);
                    continue;
                }
                try {
                    // Buscar información del libro en la API de Google Books
                    const response = await fetch(`https://www.googleapis.com/books/v1/volumes?q=${encodeURIComponent(libro.titulo)}`);
                    const data = await response.json();
                    console.log("Datos de la API para el libro:", data); // Depuración

                    if (data.items && data.items.length > 0) {
                        const book = data.items[0].volumeInfo;
                        
                        // Calcular porcentaje de compatibilidad (0-100%)
                        const compatibilidad = Math.round((libro.compatibilidad / recomendaciones[0].compatibilidad) * 100);
                        
                        const card = document.createElement("div");
                        card.className = "col-md-4 book-card";
                        card.innerHTML = `
                            <img class="book-cover" 
                                src="${book.imageLinks ? book.imageLinks.thumbnail : 'https://via.placeholder.com/150x200?text=Sin+Imagen'}" 
                                alt="${book.title}" 
                                data-title="${book.title}">
                            <div class="book-title">${book.title}</div>
                            <div class="book-author">${book.authors ? book.authors.join(", ") : "Autor desconocido"}</div>
                            <div class="compatibility-indicator">Compatibilidad: ${compatibilidad}%</div>
                        `;
                        
                        // Agregar evento de clic para mostrar detalles
                        const coverImg = card.querySelector('.book-cover');
                        coverImg.addEventListener('click', () => mostrarDetalle(book.title, book));
                        
                        container.appendChild(card);
                    } else {
                        console.warn(`No se encontró información para el libro: ${libro.titulo}`);
                    }
                } catch (error) {
                    console.error(`Error al buscar información para el libro: ${libro.titulo}`, error);
                }
            }
        }

        // Mostrar detalles del libro usando la API de Google Books
        async function mostrarDetalle(titulo, bookData) {
            const detailContainer = document.getElementById("book-detail");
            detailContainer.style.display = "none";
            
            try {
                let book = bookData;
                
                // Si no tenemos datos del libro, buscarlos en la API
                if (!book) {
                    const response = await fetch(`https://www.googleapis.com/books/v1/volumes?q=${encodeURIComponent(titulo)}`);
                    const data = await response.json();
                    if (data.items && data.items.length > 0) {
                        book = data.items[0].volumeInfo;
                    } else {
                        detailContainer.innerHTML = "<p>No se encontró información del libro.</p>";
                        detailContainer.style.display = "block";
                        return;
                    }
                }
                
                // Crear contenido HTML para los detalles del libro
                const categories = book.categories ? book.categories.map(cat => 
                    `<div class="category-tag">${cat}</div>`).join('') : 'No disponible';
                
                detailContainer.innerHTML = `
                    <button class="close-detail" onclick="cerrarDetalle()"><i class="fas fa-times"></i></button>
                    <h2>${book.title}</h2>
                    <img src="${book.imageLinks ? book.imageLinks.thumbnail : 'https://via.placeholder.com/150x200?text=Sin+Imagen'}" alt="${book.title}">
                    
                    <div class="book-meta">
                        <div>
                            <span class="meta-label">Autor</span>
                            ${book.authors ? book.authors.join(", ") : "No disponible"}
                        </div>
                        <div>
                            <span class="meta-label">Editorial</span>
                            ${book.publisher || "No disponible"}
                        </div>
                        <div>
                            <span class="meta-label">Publicación</span>
                            ${book.publishedDate || "No disponible"}
                        </div>
                        <div>
                            <span class="meta-label">Páginas</span>
                            ${book.pageCount ? book.pageCount + ' páginas' : "No disponible"}
                        </div>
                    </div>
                    
                    <p><strong>Categorías:</strong></p>
                    <div class="book-categories">
                        ${categories}
                    </div>
                    
                    <p><strong>Descripción:</strong></p>
                    <div class="book-description" id="description-${Math.random().toString(36).substring(7)}">
                        ${book.description || "No hay descripción disponible para este libro."}
                    </div>
                    <span class="expand-description" onclick="toggleDescription(this)">Leer más</span>
                `;
                
                // Mostrar el contenedor de detalles con una animación
                detailContainer.style.display = "block";
                detailContainer.scrollIntoView({ behavior: 'smooth' });
                
            } catch (error) {
                console.error("Error al cargar los detalles:", error);
                detailContainer.innerHTML = "<p>Error al cargar los detalles del libro.</p>";
                detailContainer.style.display = "block";
            }
        }
        
        // Función para cerrar los detalles del libro
        function cerrarDetalle() {
            const detailContainer = document.getElementById("book-detail");
            detailContainer.style.display = "none";
        }
        
        // Función para expandir/contraer la descripción
        function toggleDescription(element) {
            const description = element.previousElementSibling;
            description.classList.toggle('expanded');
            
            if (description.classList.contains('expanded')) {
                element.textContent = "Leer menos";
            } else {
                element.textContent = "Leer más";
                description.scrollIntoView({ behavior: 'smooth' });
            }
        }
    </script>
</body>
</html>