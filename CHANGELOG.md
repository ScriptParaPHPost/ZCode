# Changelog

## [Unreleased]

### Cambios y Mejoras

- **Funciones y Variables**
  - Redefinición para mejorar claridad y rendimiento.
- **Imágenes**
  - Actualización a formatos modernos: SVG, WebP.
- **Plugins**
  - Actualizaciones para jQuery.
  - Nuevo plugin `lite-youtube` actualizado.
- **Archivos**
  - Actualización de `QueryString.php`.
  - Reescritura completa de `live.js`.
  - Creación de archivo `.env` para configuraciones sensibles.
  - Nuevo archivo `example.config.php` (utilizado en la instalación).
- **Smarty**
  - Actualización a la última versión.
  - Mejoras avanzadas en configuración.
- **Editor**
  - Rediseño del editor WYSIWYG (**wysibb**).
- **Theme**
  - Creación de un nuevo theme desde cero con personalización de colores y modos de diseño, incluyendo guardado automático.
- **Modularización**
  - Separación de códigos y reescritura de algunas funciones.
  - Implementación de rutas en un archivo dedicado.
- **Independencia de Módulos**
  - Login, Registro, Administración y Moderación ahora son independientes.
- **PHP**
  - Compatibilidad mejorada con PHP `8.2+`.
  - Mejoras en la función para crear y verificar contraseñas.
- **Base de Datos**
  - Modificaciones en `database.php`:
    - Consultas optimizadas.
    - Nuevas consultas añadidas.
    - Prefijos actualizados para tablas (`@nombre_tabla`).
    - Eliminación de prefijos antiguos (`f_`, `p_`, `u_`, `w_`).
  - Herramientas para administración de base de datos:
    - Analizar, Optimizar, Reparar, Comprobar y Crear backups.
- **Avatares**
  - Función simplificada para gestionar avatares:
    - Avatar normal.
    - Avatar en formato GIF.
    - Selección de avatares predefinidos.
- **Publicaciones**
  - Simplificación de código para crear publicaciones en muro.
  - Soporte para subir imágenes al muro desde el portapapeles (`CTRL + V`).
- **Optimización de Código**
  - Uso de `import` en JavaScript para evitar carga innecesaria (experimental).
- **Filtros**
  - Filtro avanzado en el perfil.

### Nuevas Funcionalidades

- **Páginas de Error**
  - Nuevas páginas para errores `401`, `403`, `404` (`.html`).
- **Librerías**
  - **Portadas de posts**:
    - Generación automática de carpetas ID.
    - Creación de imágenes en tres tamaños: `120x90`, `240x180`, `480x270`.
  - **OpenGraph**:
    - Obtención de datos de URL (título, imágenes y descripción).
- **Plugins**
  - `meta`: Control avanzado de etiquetas `<head>`.
  - `zCode`: Uso extendido en themes.
  - `uicon`: Soporte para iconos SVG.
  - `human`: Conversión de números (ej. `1000 => 1K`).
  - `protected_mail`: Protección contra SPAM en correos electrónicos.
- **Diseño y Personalización**
  - Modal totalmente customizable.
  - Generador de favicon en tamaños: `16, 32, 64, 128, 512 px`.
- **Seguridad**
  - Integración de autenticación en dos pasos (2FA).
- **Sistema de Actualización**
  - Actualizaciones automatizadas desde el repositorio de GitHub.
- **Interacciones**
  - Sistema de votación tipo foro (positivo/negativo).

### Eliminados

- **CDN**
  - Todos los recursos ahora son gestionados de forma local.

### Posibles Mejoras Futuras

- **Gestión de Avatares**
  - Librería para la creación de avatares:
    - Generación automática de carpetas ID.
    - Almacenamiento de avatares subidos por los usuarios.

---

> **Nota:**  
> Elementos marcados con `(*)` fueron creados desde cero.  
> Elementos marcados con `(+)` son similares a otros existentes, pero no idénticos.
