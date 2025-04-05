<?php
include 'includes/db.php';

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $asunto = $_POST['asunto'] ?? '';
    $mensaje = $_POST['mensaje'] ?? '';
    $fecha = date('Y-m-d H:i:s');
    
    // Validar campos
    $errores = [];
    if (empty($nombre)) $errores[] = "El nombre es requerido";
    if (empty($correo)) $errores[] = "El correo es requerido";
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) $errores[] = "El correo no es válido";
    if (empty($asunto)) $errores[] = "El asunto es requerido";
    if (empty($mensaje)) $errores[] = "El mensaje es requerido";
    
    if (empty($errores)) {
        try {
            // Insertar el mensaje
            $query = "INSERT INTO contacto (fecha, nombre, correo, asunto, mensaje, estado) VALUES (?, ?, ?, ?, ?, 'nuevo')";
            $stmt = $conn->prepare($query);
            $stmt->execute([$fecha, $nombre, $correo, $asunto, $mensaje]);
            
            $mensaje_exito = "✅ ¡Gracias por tu mensaje! Te contactaremos pronto.";
            
            // Limpiar el formulario
            $nombre = $correo = $asunto = $mensaje = '';
        } catch (PDOException $e) {
            $mensaje_error = "❌ Error al enviar el mensaje. Por favor, intentalo nuevamente.";
        }
    }
}

// Obtener comentarios
try {
    $stmt = $conn->query("SELECT * FROM contacto ORDER BY fecha DESC");
    $comentarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $num_comentarios = count($comentarios);
} catch (PDOException $e) {
    $mensaje_error = "❌ Error al recuperar comentarios: " . $e->getMessage();
    $comentarios = [];
    $num_comentarios = 0;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto - Biblioteca</title>
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
                    <a class="nav-link active" href="contacto.php">
                        <i class='bx bx-envelope me-2'></i>
                        Contacto
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="main-content" id="main">
        <div class="container">
            <div class="main-title mb-4">
                <i class='bx bx-envelope fs-1 text-primary'></i>
                <h1 class="display-5 mb-0">Contacto</h1>
            </div>

            <?php if (!empty($errores)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errores as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if (isset($mensaje_exito)): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($mensaje_exito); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($mensaje_error)): ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($mensaje_error); ?>
                </div>
            <?php endif; ?>

            <div class="row justify-content-center mb-5">
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <form method="POST" action="contacto.php" class="needs-validation" novalidate>
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class='bx bx-user'></i>
                                        </span>
                                        <input type="text" class="form-control" id="nombre" name="nombre" 
                                               value="<?php echo htmlspecialchars($nombre ?? ''); ?>" required>
                                    </div>
                                    <div class="invalid-feedback">
                                        Por favor ingresa tu nombre
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="correo" class="form-label">Correo Electrónico</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class='bx bx-envelope'></i>
                                        </span>
                                        <input type="email" class="form-control" id="correo" name="correo" 
                                               value="<?php echo htmlspecialchars($correo ?? ''); ?>" required>
                                    </div>
                                    <div class="invalid-feedback">
                                        Por favor ingresa un correo válido
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="asunto" class="form-label">Asunto</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class='bx bx-chat'></i>
                                        </span>
                                        <input type="text" class="form-control" id="asunto" name="asunto" 
                                               value="<?php echo htmlspecialchars($asunto ?? ''); ?>" required>
                                    </div>
                                    <div class="invalid-feedback">
                                        Por favor ingresa el asunto
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="mensaje" class="form-label">Mensaje</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class='bx bx-message-detail'></i>
                                        </span>
                                        <textarea class="form-control" id="mensaje" name="mensaje" 
                                                  rows="5" required><?php echo htmlspecialchars($mensaje ?? ''); ?></textarea>
                                    </div>
                                    <div class="invalid-feedback">
                                        Por favor ingresa tu mensaje
                                    </div>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class='bx bx-send me-2'></i>
                                        Enviar Mensaje
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección de comentarios recibidos -->
            <div class="row justify-content-center mb-5">
                <div class="col-md-10">
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white">
                            <h2 class="mb-0 fs-4">
                                <i class='bx bx-message-square-detail me-2'></i>
                                Mensajes recibidos
                            </h2>
                        </div>
                        <div class="card-body">
                            <?php if ($num_comentarios > 0): ?>
                                <?php $contador = 1; ?>
                                <?php foreach ($comentarios as $row): ?>
                                    <div class="comentario mb-4 p-3 bg-light rounded shadow-sm border">
                                        <div class="comentario-header d-flex justify-content-between align-items-center flex-wrap mb-2">
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-primary me-2"><?= $contador++ ?></span>
                                                <span class="fw-bold"><?= htmlspecialchars($row['nombre']) ?></span>
                                                <span class="text-muted ms-3">
                                                    <i class='bx bx-envelope me-1'></i>
                                                    <?= htmlspecialchars($row['correo']) ?>
                                                </span>
                                            </div>
                                            <div class="text-muted small">
                                                <i class='bx bx-time me-1'></i>
                                                <?= htmlspecialchars($row['fecha']) ?>
                                                <span class="ms-2 badge <?= $row['estado'] === 'nuevo' ? 'bg-warning' : ($row['estado'] === 'leido' ? 'bg-info' : 'bg-success') ?>">
                                                    <?= $row['estado'] ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="comentario-asunto fw-bold text-dark mb-2">
                                            <i class='bx bx-chat me-1'></i> 
                                            <?= htmlspecialchars($row['asunto']) ?>
                                        </div>
                                        <div class="comentario-texto p-3 bg-white rounded border">
                                            <?= nl2br(htmlspecialchars($row['mensaje'])) ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="alert alert-info">
                                    <i class='bx bx-info-circle me-2'></i>
                                    No hay mensajes aún.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Alternar barra lateral
    const toggleBtn = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main');

    toggleBtn.addEventListener('click', function() {
        sidebar.classList.toggle('active');
        mainContent.classList.toggle('shifted');
    });

    // Cerrar barra lateral en móviles al hacer clic fuera
    document.addEventListener('click', function(event) {
        const isMobile = window.innerWidth <= 768;
        const isClickInsideSidebar = sidebar.contains(event.target);
        const isClickOnToggleBtn = toggleBtn.contains(event.target);

        if (isMobile && !isClickInsideSidebar && !isClickOnToggleBtn && sidebar.classList.contains('active')) {
            sidebar.classList.remove('active');
            mainContent.classList.remove('shifted');
        }
    });

    // Validación del formulario
    (function () {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
    })()
    </script>
</body>
</html> 