<?php
include 'includes/db.php';

// Obtener estadísticas
try {
    // Total de libros
    $query_libros = "SELECT COUNT(*) as total FROM titulos";
    $stmt_libros = $conn->query($query_libros);
    $total_libros = $stmt_libros->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Total de autores
    $query_autores = "SELECT COUNT(*) as total FROM autores";
    $stmt_autores = $conn->query($query_autores);
    $total_autores = $stmt_autores->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Libros recientes
    $query_recientes = "SELECT t.id_titulo, t.titulo, t.tipo, t.fecha_pub, 
                        a.id_autor, a.nombre, a.apellido
                        FROM titulos t
                        LEFT JOIN titulo_autor ta ON t.id_titulo = ta.id_titulo
                        LEFT JOIN autores a ON ta.id_autor = a.id_autor
                        ORDER BY t.fecha_pub DESC
                        LIMIT 4";
    $stmt_recientes = $conn->query($query_recientes);
    $libros_recientes = $stmt_recientes->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error al obtener estadísticas: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca Virtual - Inicio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <button class="toggle-sidebar" id="toggleSidebar">
        <i class='bx bx-menu fs-3'></i>
    </button>

    <nav class="sidebar" id="sidebar">
        <div class="d-flex flex-column">
            <a class="navbar-brand mb-4 px-4" href="index.php">
                <i class='bx bx-library'></i>
                Biblioteca
            </a>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="libros.php">
                        <i class='bx bx-book-open me-2'></i>
                        Libros
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="autores.php">
                        <i class='bx bx-user me-2'></i>
                        Autores
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contacto.php">
                        <i class='bx bx-envelope me-2'></i>
                        Contacto
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="main-content" id="main">
        <div class="container">
            <!-- Hero Section -->
            <div class="row mb-5 align-items-center">
                <div class="col-lg-6">
                    <div class="main-title mb-4">
                        <i class='bx bx-library fs-1 text-primary'></i>
                        <h1 class="display-4 mb-0">Bienvenido a la Biblioteca Virtual</h1>
                    </div>
                    <p class="lead mb-4">Explora nuestra colección de libros y descubre nuevos autores. Accede a un catálogo completo de títulos organizados para facilitar tu búsqueda.</p>
                    <div class="d-flex gap-3">
                        <a href="libros.php" class="btn btn-primary btn-lg">
                            <i class='bx bx-book-open me-2'></i>
                            Explorar Libros
                        </a>
                        <a href="autores.php" class="btn btn-outline-primary btn-lg">
                            <i class='bx bx-user me-2'></i>
                            Ver Autores
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 text-center mt-4 mt-lg-0">
                    <img src="https://img.freepik.com/free-vector/hand-drawn-flat-design-stack-books_23-2149334862.jpg" alt="Biblioteca" class="img-fluid rounded shadow" style="max-height: 350px;">
                </div>
            </div>

            <!-- Stats Section -->
            <div class="row mb-5">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="display-4 text-primary me-3">
                                <i class='bx bx-book'></i>
                            </div>
                            <div>
                                <h3 class="fs-2"><?php echo $total_libros; ?></h3>
                                <p class="text-muted mb-0">Libros en el catálogo</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mt-3 mt-md-0">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="display-4 text-primary me-3">
                                <i class='bx bx-user'></i>
                            </div>
                            <div>
                                <h3 class="fs-2"><?php echo $total_autores; ?></h3>
                                <p class="text-muted mb-0">Autores registrados</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Books Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="border-start border-4 border-primary ps-3 mb-4">Libros añadidos recientemente</h2>
                </div>
            </div>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 mb-5">
                <?php foreach ($libros_recientes as $libro): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <span class="badge bg-primary mb-2"><?php echo htmlspecialchars($libro['tipo']); ?></span>
                            <h5 class="card-title"><?php echo htmlspecialchars($libro['titulo']); ?></h5>
                            <?php if (!empty($libro['nombre'])): ?>
                                <p class="card-text">
                                    <i class='bx bx-user me-2'></i>
                                    <?php echo htmlspecialchars($libro['nombre'] . ' ' . $libro['apellido']); ?>
                                </p>
                            <?php endif; ?>
                            <p class="card-text small text-muted">
                                <i class='bx bx-calendar me-2'></i>
                                <?php echo date('d/m/Y', strtotime($libro['fecha_pub'])); ?>
                            </p>
                        </div>
                        <div class="card-footer bg-transparent border-top-0">
                            <button class="btn btn-primary btn-sm w-100" onclick="verDetallesLibro('<?php echo $libro['id_titulo']; ?>')">
                                <i class='bx bx-info-circle me-2'></i>
                                Ver detalles
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Features Section -->
            <div class="row mb-5">
                <div class="col-12 mb-4">
                    <h2 class="border-start border-4 border-primary ps-3">Características principales</h2>
                </div>
                <div class="col-md-4 mb-4 mb-md-0">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="display-5 text-primary mb-3">
                                <i class='bx bx-search'></i>
                            </div>
                            <h3 class="h4">Búsqueda avanzada</h3>
                            <p class="card-text text-muted">Encuentra fácilmente cualquier libro usando nuestro sistema de búsqueda en tiempo real.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4 mb-md-0">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="display-5 text-primary mb-3">
                                <i class='bx bx-book-reader'></i>
                            </div>
                            <h3 class="h4">Información completa</h3>
                            <p class="card-text text-muted">Accede a detalles completos de cada libro, incluyendo tipo, precio y fecha de publicación.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="display-5 text-primary mb-3">
                                <i class='bx bx-group'></i>
                            </div>
                            <h3 class="h4">Perfiles de autores</h3>
                            <p class="card-text text-muted">Descubre bibliografías y toda la obra publicada por cada autor en nuestra base de datos.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para detalles del libro -->
    <div class="modal fade" id="libroModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalles del Libro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="libroModalContent">
                    <!-- El contenido se cargará dinámicamente -->
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Toggle sidebar
    const toggleBtn = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main');

    toggleBtn.addEventListener('click', function() {
        sidebar.classList.toggle('active');
        mainContent.classList.toggle('shifted');
    });

    // Cerrar sidebar en móviles al hacer clic fuera
    document.addEventListener('click', function(event) {
        const isMobile = window.innerWidth <= 768;
        const isClickInsideSidebar = sidebar.contains(event.target);
        const isClickOnToggleBtn = toggleBtn.contains(event.target);

        if (isMobile && !isClickInsideSidebar && !isClickOnToggleBtn && sidebar.classList.contains('active')) {
            sidebar.classList.remove('active');
            mainContent.classList.remove('shifted');
        }
    });

    // Función para ver detalles del libro
    function verDetallesLibro(idLibro) {
        fetch(`detalles_libro.php?id=${idLibro}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }

                // Función para escapar texto HTML
                const escapeHtml = (str) => {
                    if (!str) return '';
                    return str
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;')
                        .replace(/"/g, '&quot;')
                        .replace(/'/g, '&#039;');
                };

                let content = `
                    <div class="libro-details">
                        <h4>${escapeHtml(data.titulo)}</h4>
                        <div class="mb-3">
                            <span class="badge bg-primary">${escapeHtml(data.tipo)}</span>
                        </div>
                        <div class="info-section mb-3">
                            <p><i class='bx bx-user'></i> <strong>Autor:</strong> ${escapeHtml(data.autor)}</p>
                            <p><i class='bx bx-calendar'></i> <strong>Fecha de publicación:</strong> ${data.fecha_pub}</p>
                            <p><i class='bx bx-dollar'></i> <strong>Precio:</strong> $${data.precio}</p>
                        </div>
                        <div class="notas-section">
                            <h5>Notas</h5>
                            <p class="text-muted">${escapeHtml(data.notas)}</p>
                        </div>
                    </div>
                `;

                document.getElementById('libroModalContent').innerHTML = content;
                const modal = new bootstrap.Modal(document.getElementById('libroModal'));
                modal.show();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al cargar los detalles del libro: ' + error.message);
            });
    }
    </script>
</body>
</html>