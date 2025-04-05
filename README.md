# Sistema de Gestión de Biblioteca Virtual

Este proyecto es un sistema de gestión de biblioteca virtual desarrollado como trabajo final. El sistema permite administrar libros y autores de manera eficiente y organizada.

## Características Principales

- **Gestión de Libros**: Permite ver, agregar y gestionar el catálogo de libros.
- **Gestión de Autores**: Facilita el manejo de información de autores y sus obras.
- **Interfaz Intuitiva**: Diseño moderno y responsive para una mejor experiencia de usuario.
- **Búsqueda Avanzada**: Funcionalidad para buscar libros y autores.
- **Detalles Completos**: Vista detallada de libros y autores con toda su información relevante.

## Tecnologías Utilizadas

- PHP
- MySQL
- HTML5
- CSS3
- JavaScript
- Bootstrap
- PDO para conexiones seguras a la base de datos

## Estructura del Proyecto

```
Biblioteca/
├── includes/
│   └── db.php
├── css/
├── js/
├── autores.php
├── libros.php
├── detalles_autor.php
├── detalles_libro.php
└── index.php
```

## Funcionalidades

1. **Página Principal**
   - Vista general de la biblioteca
   - Acceso rápido a libros y autores

2. **Sección de Libros**
   - Listado completo de libros
   - Detalles de cada libro
   - Información sobre disponibilidad

3. **Sección de Autores**
   - Catálogo de autores
   - Biografías y obras publicadas
   - Detalles completos de cada autor

## Instalación

1. Clonar el repositorio:
```bash
git clone https://github.com/Walki-crypto/Proyecto_Final_Code.git
```

2. Configurar la base de datos:
   - Importar el archivo SQL de la estructura de la base de datos
   - Configurar las credenciales en `includes/db.php`

3. Configurar el servidor web (Apache/XAMPP) para servir el proyecto

## Uso

1. Acceder a la página principal
2. Navegar por las diferentes secciones usando el menú de navegación
3. Utilizar los botones de acción para ver detalles o realizar búsquedas

## Contribución

Las contribuciones son bienvenidas. Para contribuir:

1. Fork el proyecto
2. Crea una rama para tu función (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## Autor

- Desarrollado como proyecto final del curso
- Año: 2025
