<?php
include 'includes/db.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Libros</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Modal de Detalles -->
    <div class="modal fade" id="detallesModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalles del Libro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <strong>Título:</strong>
                        <p id="modalTitulo"></p>
                    </div>
                    <div class="mb-3">
                        <strong>Autor:</strong>
                        <p id="modalAutor"></p>
                    </div>
                    <div class="mb-3">
                        <strong>Tipo:</strong>
                        <p id="modalTipo"></p>
                    </div>
                    <div class="mb-3">
                        <strong>Precio:</strong>
                        <p id="modalPrecio"></p>
                    </div>
                    <div class="mb-3">
                        <strong>Avance:</strong>
                        <p id="modalAvance"></p>
                    </div>
                    <div class="mb-3">
                        <strong>Ventas:</strong>
                        <p id="modalVentas"></p>
                    </div>
                    <div class="mb-3">
                        <strong>Fecha de Publicación:</strong>
                        <p id="modalFecha"></p>
                    </div>
                    <div class="mb-3">
                        <strong>Contrato:</strong>
                        <p id="modalContrato"></p>
                    </div>
                    <div class="mb-3">
                        <strong>Notas:</strong>
                        <p id="modalNotas"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

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
                    <a class="nav-link active" href="libros.php">
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
        <div class="container-fluid">
            <div class="main-title">
                <img src="img/book-icon.png" alt="Libros" onerror="this.src='data:image/svg+xml,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'40\' height=\'40\' fill=\'%230d6efd\' viewBox=\'0 0 16 16\'><path d=\'M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.935-.53-2.12-.603-3.213-.493-1.18.12-2.37.461-3.287.811V2.828zm7.5-.141c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492V2.687zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783z\'/></svg>'">
                <h1 class="display-5 mb-0">Listado de Libros</h1>
            </div>

            <div class="row mb-4">
                <div class="col-12">
                    <div class="input-group">
                        <input type="text" id="searchInput" class="form-control" placeholder="Buscar libros...">
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
                            <th>Título</th>
                            <th>Tipo</th>
                            <th>Precio</th>
                            <th>Avance</th>
                            <th>Ventas</th>
                            <th>Notas</th>
                            <th>Publicación</th>
                            <th>Contrato</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT t.id_titulo, t.titulo, t.tipo, t.precio, t.avance, t.notas, t.fecha_pub, t.contrato
                                  FROM titulos t
                                  ORDER BY t.titulo";
                        $stmt = $conn->query($query);

                        if ($stmt->rowCount() > 0) {
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr class='libro-row' data-id='" . $row['id_titulo'] . "' style='cursor: pointer;'>";
                                echo "<td>" . htmlspecialchars($row['titulo']) . "</td>";
                                echo "<td><span class='badge bg-info'>" . htmlspecialchars($row['tipo']) . "</span></td>";
                                echo "<td>$" . number_format($row['precio'], 2) . "</td>";
                                echo "<td>" . (!empty($row['avance']) ? '$' . number_format($row['avance'], 0) : 'N/A') . "</td>";
                                echo "<td>N/A</td>"; // Sin ventas disponibles
                                echo "<td class='text-truncate' style='max-width: 300px;'>" . (!empty($row['notas']) ? htmlspecialchars($row['notas']) : '') . "</td>";
                                echo "<td>" . date('d/m/Y', strtotime($row['fecha_pub'])) . "</td>";
                                echo "<td>" . ($row['contrato'] ? 'Sí' : 'No') . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8' class='text-center'>No hay libros disponibles</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p class="mb-0">&copy; 2025 Biblioteca Online. Todos los derechos reservados.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Búsqueda en tiempo real
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let input = this.value.toLowerCase();
        let rows = document.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            let text = row.textContent.toLowerCase();
            row.style.display = text.includes(input) ? '' : 'none';
        });
    });

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

    // Modal de detalles
    const detallesModal = new bootstrap.Modal(document.getElementById('detallesModal'));
    
    // Función para cargar detalles del libro
    function cargarDetallesLibro(id) {
        fetch(`detalles_libro.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    return;
                }
                
                document.getElementById('modalTitulo').textContent = data.titulo || 'No disponible';
                document.getElementById('modalAutor').textContent = data.autor || 'No disponible';
                document.getElementById('modalTipo').textContent = data.tipo || 'No disponible';
                document.getElementById('modalPrecio').textContent = data.precio ? '$' + data.precio : 'No disponible';
                document.getElementById('modalAvance').textContent = data.avance ? '$' + data.avance : 'No disponible';
                document.getElementById('modalVentas').textContent = data.ventas || 'No disponible';
                document.getElementById('modalFecha').textContent = data.fecha_pub || 'No disponible';
                document.getElementById('modalContrato').textContent = data.contrato ? 'Sí' : 'No';
                document.getElementById('modalNotas').textContent = data.notas || 'Sin notas';
                
                detallesModal.show();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al cargar los detalles del libro');
            });
    }

    // Event listeners para filas
    document.querySelectorAll('.libro-row').forEach(row => {
        row.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            cargarDetallesLibro(id);
        });
    });
    </script>
</body>
</html>