<!-- 
 * [ADAN ZAMORA] [LUIS RAMOS]: Este código implementa una interfaz para buscar y mostrar libros mediante la API de Google Books. 
 * Permite a los usuarios buscar libros con sugerencias y visualizar resultados en forma 
 * de tarjetas, y explorar los detalles completos de un libro seleccionado. También incluye un sistema 
 * interactivo para gestionar descripciones resumidas y completas, además de la capacidad  de cargar y 
 * mostrar reseñas. Todo está diseñado para ser responsivo y fácil de usar, con funciones como volver a 
 * la lista de resultados y botones interactivos.
-->

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Biblioteca - Búsqueda de Libros</title>
    <!-- Incluir Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Sugerencias de autocompletar */
        .suggestions {
            border: 1px solid #ccc;
            max-height: 150px;
            overflow-y: auto;
            background-color: #fff;
            position: absolute;
            width: 100%;
            z-index: 1000;
        }

        /* Estilos para los elementos de sugerencias */
        .suggestion-item {
            padding: 10px;
            cursor: pointer;
        }
        .suggestion-item:hover {
            background-color: #f0f0f0;
        }
        
        /* Tarjeta de libro para la vista en dos columnas */
        .book-card {
            display: flex;
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 15px;
            background-color: #f9f9f9;
            margin-bottom: 20px;
            width: 100%;
        }
        .book-card img {
            width: 100px;
            height: 150px;
            margin-right: 15px;
        }
        .book-title {
            font-size: 18px;
            margin-bottom: 5px;
        }
        .book-details {
            font-size: 14px;
            color: #555;
        }
        
        /* Diseño de detalles del libro */
        .book-detail {
            display: none;
            padding: 20px;
            border-radius: 4px;
            background-color: #f8f9fa;
        }
        .detail-image {
            float: left;
            width: 150px;
            height: 225px;
            margin-right: 20px;
            border-radius: 4px;
        }
        .detail-info {
            margin-left: 170px;
        }
        
        /* Estilos para el botón de volver */
        .back-btn {
            display: block;
            margin-bottom: 20px;
            color: #007bff;
        }
        .loading {
            display: none;
            text-align: center;
            margin: 20px;
        }
        
        /* Ajustes para resultados en filas */
        .results-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        /* Estilo para el botón de alternar descripción */
        .toggle-btn {
            background-color: transparent;
            border: none;
            color: #007bff;
            cursor: pointer;
            padding: 0;
            font-size: 14px;
            margin-left: 5px;
        }
        .toggle-btn:hover {
            text-decoration: underline;
        }

        /* Estilo para el contenedor de reseñas */
        .book-id {
            position: absolute;
            top: 0;
            right: 0;
            background-color: #fff;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 12px;
            color: #555;
        }

        .back-to-home-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            color: #fff;
            background-color: #ff8c00;
            padding: 10px;
            border-radius: 50%;
            text-decoration: none; /* Añade esta línea */
            border: none;
            transition: background-color 0.3s ease, transform 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .back-to-home-btn:hover {
            background-color: #e67e22 !important;
            transform: scale(1.1);
            color: #fff;
            text-decoration: none; /* Y también aquí para asegurar */
        }
        .back-to-home-btn i {
            font-size: 20px;
        }

        /* Estilos para el contenedor de búsqueda */
        .search-container {
            position: relative;
        }
        .search-input {
            padding-right: 40px;
        }
        .search-button {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
        }
        .search-button i {
            font-size: 20px;
            color: #007bff;
        }
        .suggestion-item.highlight {
            background-color: #e9ecef;
        }
    </style>
</head>
<body>
    <a href="home.php" class="back-to-home-btn">
        <i class="fas fa-home"></i>
    </a>
    <div class="container mt-5">
        <div class="search-container">
            <input type="text" 
                   class="form-control search-input" 
                   placeholder="Buscar libros..."
                   id="searchInput">
            <button class="search-button" onclick="triggerSearch()">
                <i class="fas fa-search"></i>
            </button>
            <div class="suggestions" id="suggestions"></div>
        </div>
        <div class="loading" id="loading">Buscando libros...</div>
        <div class="results-container mt-4" id="results"></div>
        <div id="book-detail" class="book-detail mt-4"></div>
    </div>
    <!-- Incluir Bootstrap JS y dependencias -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script>
        // Obtener referencias a los elementos del DOM
        const searchInput = document.getElementById('searchInput');
        const suggestionsContainer = document.getElementById('suggestions');
        const resultsContainer = document.getElementById('results');
        const bookDetailContainer = document.getElementById('book-detail');
        const loadingIndicator = document.getElementById('loading');
        let selectedSuggestionIndex = -1;

        // Función para iniciar la búsqueda
        function triggerSearch() {
            const query = searchInput.value.trim();
            if (query) {
                suggestionsContainer.style.display = 'none';
                searchBooks(query);
            }
        }

        // Función principal de búsqueda
        async function searchBooks(query) {
            loadingIndicator.style.display = 'block';
            resultsContainer.innerHTML = '';
            bookDetailContainer.style.display = 'none';
            try {
                // Realizar la solicitud a la API de Google Books
                const response = await fetch(`https://www.googleapis.com/books/v1/volumes?q=${encodeURIComponent(query)}`);
                const data = await response.json();
                if (data.items) {
                    // Mostrar los resultados de la búsqueda
                    data.items.forEach(book => {
                        const bookInfo = book.volumeInfo;
                        const card = document.createElement('div');
                        card.className = 'book-card';
                        
                        // Contenido HTML para la tarjeta del libro
                        card.innerHTML = `
                            <img src="${bookInfo.imageLinks ? bookInfo.imageLinks.thumbnail : 'https://via.placeholder.com/128x190'}" 
                                 alt="${bookInfo.title}">
                            <div>
                                <h2 class="book-title">${bookInfo.title}</h2>
                                <div class="book-details">
                                    <p><strong>Autores:</strong> ${bookInfo.authors ? bookInfo.authors.join(', ') : 'No disponible'}</p>
                                    <p><strong>Editorial:</strong> ${bookInfo.publisher || 'No disponible'}</p>
                                    <p><strong>Fecha de publicación:</strong> ${bookInfo.publishedDate || 'No disponible'}</p>
                                </div>
                                <button class="btn btn-primary view-more-btn" onclick="showBookDetail('${book.id}')">
                                    Ver más
                                </button>
                            </div>
                        `;

                        resultsContainer.appendChild(card);
                    });
                } else {
                    resultsContainer.innerHTML = '<p>No se encontraron resultados.</p>';
                }
            } catch (error) {
                resultsContainer.innerHTML = '<p>Error al buscar libros.</p>';
                console.error('Error:', error);
            }

            loadingIndicator.style.display = 'none'; // Ocultar indicador de carga
        }

        // Función para limpiar texto HTML
        function stripHTML(html) {
            const temp = document.createElement('div');
            temp.innerHTML = html;
            return temp.textContent || temp.innerText;
        }

        // Función para obtener descripción limpia y corta
        function getDescriptions(description, maxLength = 500) {
            const cleanDescription = stripHTML(description || 'No hay descripción disponible');
            const shortDescription = cleanDescription.length > maxLength 
                ? cleanDescription.substring(0, maxLength) + '...' 
                : cleanDescription;
            
            return { cleanDescription, shortDescription };
        }

        // Función modificada para cargar reseñas del libro usando ID en lugar de título
        async function loadReviews(bookTitle, page = 1, bookId = null) {
            try {
                let url;
                if (bookId) {
                    // Usar ID del libro si está disponible
                    url = `resenas.php?book_id=${encodeURIComponent(bookId)}&page=${page}`;
                    console.log('Cargando reseñas para ID:', bookId);
                } else {
                    // Fallback al título si no hay ID
                    const encodedTitle = encodeURIComponent(bookTitle.trim());
                    url = `resenas.php?book=${encodedTitle}&page=${page}`;
                    console.log('Cargando reseñas para título:', bookTitle);
                }
                
                const response = await fetch(url);
                const reviewsHtml = await response.text();
                const reviewsContainer = document.getElementById('reviews');
                reviewsContainer.innerHTML = reviewsHtml;

                // Agregar event listeners a los enlaces de paginación
                const paginationLinks = reviewsContainer.querySelectorAll('.pagination a');
                paginationLinks.forEach(link => {
                    link.addEventListener('click', async (e) => {
                        e.preventDefault();
                        const url = new URL(link.href);
                        const newPage = url.searchParams.get('page');
                        await loadReviews(bookTitle, newPage, bookId); // Pasar bookId también
                        reviewsContainer.scrollIntoView({ behavior: 'smooth' });
                    });
                });
            } catch (error) {
                document.getElementById('reviews').innerHTML = '<p>Error al cargar las reseñas.</p>';
                console.error('Error:', error);
            }
        }

        // Función principal para mostrar detalles del libro
        async function showBookDetail(bookId) {
            loadingIndicator.style.display = 'block';
            resultsContainer.style.display = 'none';
            suggestionsContainer.style.display = 'none';
            document.querySelector('.search-container').style.display = 'none';

            try {
                const response = await fetch(`https://www.googleapis.com/books/v1/volumes/${bookId}`);
                const book = await response.json();
                const bookInfo = book.volumeInfo;
                
                const { cleanDescription, shortDescription } = getDescriptions(bookInfo.description);
                const showExpandButton = cleanDescription.length > shortDescription.length;
                
                bookDetailContainer.innerHTML = `
                    <a href="#" class="back-btn" onclick="goBack()">← Volver a resultados</a>
                    <div style="position: relative;">
                        <img src="${bookInfo.imageLinks ? bookInfo.imageLinks.thumbnail : 'https://via.placeholder.com/300x400'}" 
                            alt="${bookInfo.title}" 
                            class="detail-image">
                        <span class="book-id">
                            ID: ${book.id}
                        </span>
                    </div>
                    <div class="detail-info">
                        <h1 class="book-title">${bookInfo.title}</h1>
                        <p><strong>Autores:</strong> ${bookInfo.authors ? bookInfo.authors.join(', ') : 'No disponible'}</p>
                        <p><strong>Categoría:</strong> ${bookInfo.categories ? bookInfo.categories.join(', ') : 'No disponible'}</p>
                        <p><strong>Editorial:</strong> ${bookInfo.publisher || 'No disponible'}</p>
                        <p><strong>Fecha de publicación:</strong> ${bookInfo.publishedDate || 'No disponible'}</p>
                        <p><strong>Número de páginas:</strong> ${bookInfo.pageCount || 'No disponible'}</p>
                        <p><strong>Descripción:</strong></p>
                        <div id="description-container" class="description-text">
                            <p id="book-description">${shortDescription}</p>
                            ${showExpandButton ? `<button id="toggle-description" class="toggle-btn" onclick="toggleDescription(this)">Ver más</button>` : ''}
                        </div>
                        <div id="reviews-container" class="reviews-container mt-4">
                            <h2>Reseñas</h2>
                            <div id="reviews"></div>
                        </div>
                    </div>
                `;

                const descriptionContainer = document.getElementById('description-container');
                descriptionContainer.dataset.fullDescription = cleanDescription;
                descriptionContainer.dataset.shortDescription = shortDescription;

                // Cargar reseñas usando el ID del libro
                loadReviews(bookInfo.title, 1, book.id);

                bookDetailContainer.style.display = 'block'; 
            } catch (error) {
                bookDetailContainer.innerHTML = '<p>Error al cargar los detalles del libro.</p>';
                console.error('Error:', error);
            }

            loadingIndicator.style.display = 'none';
        }

        // Función para alternar entre descripción completa y resumida
        function toggleDescription(button) {
            const container = document.getElementById('description-container');
            const descriptionElement = document.getElementById('book-description');
            const isExpanded = button.textContent === 'Ver menos';
            
            descriptionElement.textContent = isExpanded 
                ? container.dataset.shortDescription // Mostrar descripción corta
                : container.dataset.fullDescription; // Mostrar descripción completa
            
            button.textContent = isExpanded ? 'Ver más' : 'Ver menos';
        }

        
        // Función para volver a los resultados
        function goBack() {
            bookDetailContainer.style.display = 'none';
            resultsContainer.style.display = 'grid';
            document.querySelector('.search-container').style.display = 'block'; // Mostrar barra de búsqueda
        }

        // Event listeners
        searchInput.addEventListener('input', (e) => { // Obtener sugerencias al escribir
            const query = e.target.value.trim();
            getSuggestions(query);
        });
        searchInput.addEventListener('keypress', (e) => { // Realizar búsqueda al presionar Enter
            if (e.key === 'Enter') {
                const query = e.target.value.trim(); // Obtener el texto de búsqueda
                if (query) { 
                    suggestionsContainer.style.display = 'none'; // Ocultar sugerencias
                    searchBooks(query);
                }
            }
        });
        searchInput.addEventListener('keydown', handleKeyDown); // Navegación con teclado en sugerencias
        document.addEventListener('click', (e) => {
            if (!suggestionsContainer.contains(e.target) && e.target !== searchInput) {
                suggestionsContainer.style.display = 'none';
            }
        });

        // Función para obtener sugerencias
        async function getSuggestions(query) {
            if (!query) {
                suggestionsContainer.innerHTML = '';
                return;
            }
            try {
                // Realizar la solicitud a la API de Google Books para obtener sugerencias
                const response = await fetch(`https://www.googleapis.com/books/v1/volumes?q=${encodeURIComponent(query)}`);
                const data = await response.json();
                suggestionsContainer.innerHTML = '';
                if (data.items) {
                    // Mostrar las sugerencias
                    data.items.forEach((book, index) => {
                        const suggestionItem = document.createElement('div');
                        suggestionItem.className = 'suggestion-item'; 
                        suggestionItem.textContent = book.volumeInfo.title; 
                        suggestionItem.addEventListener('click', () => {
                            searchInput.value = book.volumeInfo.title; // Rellenar el campo de búsqueda
                            suggestionsContainer.innerHTML = ''; // Limpiar sugerencias
                            searchBooks(book.volumeInfo.title); // Realizar la búsqueda
                        });
                        suggestionsContainer.appendChild(suggestionItem); // Agregar sugerencia al contenedor
                    });
                    suggestionsContainer.style.display = 'block'; // Mostrar contenedor de sugerencias
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }

        // Función para manejar la navegación con el teclado en las sugerencias
        function handleKeyDown(event) {
            const suggestions = suggestionsContainer.querySelectorAll('.suggestion-item'); // Obtener todas las sugerencias
            if (suggestions.length > 0) { 
                if (event.key === 'ArrowDown') {
                    selectedSuggestionIndex = (selectedSuggestionIndex + 1) % suggestions.length; 
                    updateSuggestionHighlight(suggestions); // Actualizar el resaltado de la sugerencia
                } else if (event.key === 'ArrowUp') {
                    selectedSuggestionIndex = (selectedSuggestionIndex - 1 + suggestions.length) % suggestions.length; // Calcular el índice anterior
                    updateSuggestionHighlight(suggestions); 
                } else if (event.key === 'Enter') {
                    if (selectedSuggestionIndex >= 0) {
                        suggestions[selectedSuggestionIndex].click(); // Simular clic en la sugerencia seleccionada
                    } else {
                        triggerSearch();
                    }
                }
            }
        }

        // Función para actualizar el resaltado de las sugerencias
        function updateSuggestionHighlight(suggestions) {
            suggestions.forEach((item, index) => {
                item.classList.toggle('highlight', index === selectedSuggestionIndex);
            });
        }
    </script>
</body>
</html>