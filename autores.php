<?php
include 'includes/db.php';

try {
    // Consulta para obtener autores y contar sus libros
    $query = "SELECT a.*, 
              COUNT(DISTINCT ta.id_titulo) as total_libros,
              GROUP_CONCAT(DISTINCT t.titulo SEPARATOR '||') as titulos,
              GROUP_CONCAT(DISTINCT t.tipo SEPARATOR '||') as tipos,
              b.biografia
              FROM autores a
              LEFT JOIN titulo_autor ta ON a.id_autor = ta.id_autor
              LEFT JOIN titulos t ON ta.id_titulo = t.id_titulo
              LEFT JOIN biografias b ON a.id_autor = b.id_autor
              GROUP BY a.id_autor
              ORDER BY a.apellido, a.nombre";
              
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $autores = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Error al obtener los autores: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autores - Biblioteca</title>
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
                    <a class="nav-link active" href="autores.php">
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
        <div class="container-fluid">
            <div class="main-title">
                <img src="img/author-icon.png" alt="Autores" onerror="this.src='data:image/svg+xml,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'40\' height=\'40\' fill=\'%230d6efd\' viewBox=\'0 0 16 16\'><path d=\'M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3Zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z\'/></svg>'">
                <h1 class="display-5 mb-0">Listado de Autores</h1>
            </div>

            <div class="row mb-4">
                <div class="col-12">
                    <div class="input-group">
                        <input type="text" id="searchInput" class="form-control" placeholder="Buscar autores...">
                        <button class="btn btn-primary" type="button">
                            <i class='bx bx-search'></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID Autor</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Ciudad</th>
                            <th>Código Postal</th>
                            <th>Dirección</th>
                            <th>Estado</th>
                            <th>País</th>
                            <th>Teléfono</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (count($autores) > 0) {
                            foreach ($autores as $autor) {
                                echo "<tr class='autor-row' onclick='verDetallesAutor(\"" . $autor['id_autor'] . "\")' style='cursor: pointer;'>";
                                echo "<td>" . htmlspecialchars($autor['id_autor']) . "</td>";
                                echo "<td>" . htmlspecialchars($autor['nombre']) . "</td>";
                                echo "<td>" . htmlspecialchars($autor['apellido']) . "</td>";
                                echo "<td>" . htmlspecialchars($autor['ciudad']) . "</td>";
                                echo "<td>" . htmlspecialchars($autor['cod_postal']) . "</td>";
                                echo "<td>" . htmlspecialchars($autor['direccion']) . "</td>";
                                echo "<td>" . htmlspecialchars($autor['estado']) . "</td>";
                                echo "<td>" . htmlspecialchars($autor['pais']) . "</td>";
                                echo "<td>" . htmlspecialchars($autor['telefono']) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='9' class='text-center'>No hay autores disponibles</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal para detalles del autor -->
    <div class="modal fade" id="autorModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalles del Autor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="modalContent">
                    <!-- El contenido se cargará dinámicamente -->
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p class="mb-0">&copy; 2025 Biblioteca Online. Todos los derechos reservados.</p>
    </footer>

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

    // Búsqueda en tiempo real
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let input = this.value.toLowerCase();
        let rows = document.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            let text = row.textContent.toLowerCase();
            row.style.display = text.includes(input) ? '' : 'none';
        });
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

    // Función para ver detalles del autor
    function verDetallesAutor(idAutor) {
        fetch(`detalles_autor.php?id=${idAutor}`)
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

                const autor = data.autor;
                const libros = data.libros;

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

                let modalContent = `
                    <div class="autor-details">
                        <h4>${escapeHtml(autor.nombre)} ${escapeHtml(autor.apellido)}</h4>
                        <div class="info-section mb-4">
                            <p><i class='bx bx-map-pin'></i> ${escapeHtml(autor.direccion)}</p>
                            <p><i class='bx bx-building'></i> ${escapeHtml(autor.ciudad)}, ${escapeHtml(autor.estado)}, ${escapeHtml(autor.pais)}</p>
                            <p><i class='bx bx-envelope'></i> CP: ${escapeHtml(autor.cod_postal)}</p>
                            <p><i class='bx bx-phone'></i> ${escapeHtml(autor.telefono)}</p>
                        </div>`;

                if (autor.biografia) {
                    modalContent += `
                        <div class="biografia-section mb-4">
                            <h5>Biografía</h5>
                            <p class="text-muted">${escapeHtml(autor.biografia)}</p>
                        </div>`;
                }

                if (libros && libros.length > 0) {
                    modalContent += `
                        <div class="libros-section">
                            <h5>Libros publicados (${libros.length})</h5>
                            <div class="table-responsive">
                                <table class="table table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Título</th>
                                            <th>Tipo</th>
                                            <th>Precio</th>
                                        </tr>
                                    </thead>
                                    <tbody>`;
                    
                    libros.forEach(libro => {
                        modalContent += `
                            <tr>
                                <td>${escapeHtml(libro.titulo)}</td>
                                <td><span class="badge bg-info">${escapeHtml(libro.tipo)}</span></td>
                                <td>$${libro.precio}</td>
                            </tr>`;
                    });
                    
                    modalContent += `
                                    </tbody>
                                </table>
                            </div>
                        </div>`;
                } else {
                    modalContent += `
                        <div class="alert alert-warning">
                            <i class='bx bx-error me-2'></i> Este autor no tiene libros publicados en nuestra base de datos.
                        </div>`;
                }

                modalContent += `</div>`;
                
                document.getElementById('modalContent').innerHTML = modalContent;
                
                const autorModal = new bootstrap.Modal(document.getElementById('autorModal'));
                autorModal.show();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al cargar los detalles del autor: ' + error.message);
            });
    }
    </script>
</body>
</html>