![Repositorio](https://img.shields.io/github/repo-size/ScriptParaPHPost/ZCode?style=flat)
![ZCode v3.1.18](https://img.shields.io/badge/ZCode-v3.1.18-teal?style=flat)
![PHP 8](https://img.shields.io/badge/PHP-8.2.16-teal?style=flat)
![Smarty 5+](https://img.shields.io/badge/Smarty-5.4.3-teal?style=flat)

# ZCode v3
Es una versión enfocada en la modernización del núcleo del sistema, incorporando mejoras arquitectónicas clave y actualizaciones de dependencias fundamentales. Esta versión introduce una estructura más escalable y mantenible, facilita las pruebas automatizadas y mejora la seguridad general de la plataforma.

### ✅ Novedades destacadas
- Se mejoró la estructura del sistema con uso de `interface`, `namespace`, y `traits`
- Nueva clase `Autenticar`: gestiona login/logout de forma unificada y segura
- Protección CSRF implementada en formularios
- Reorganización de sesiones: ahora separadas del controlador `User.php` mediante la clase `Session`
- Inyección de dependencias incorporada: más desacoplamiento y testeo fácil (_aplicandose_)

### 📚 Dependencias actualizadas / nuevas
Estas son las principales librerías utilizadas por ZCode v3:
- **Smarty 5.4.3** – motor de plantillas moderno y potente
- **PHP dotenv** – configuración segura a través de archivos .env
- **Symfony Cache** – sistema de caché flexible y de alto rendimiento
- **OTPHP** – autenticación en dos pasos compatible con Google Authenticator
- **Composer Patches** – permite aplicar parches personalizados a paquetes
- **JBBCode** – parser BBCode

## 🛠 Instalación

### ⚙️ Requisitos

- PHP **8.2 o superior**
- [Composer](https://getcomposer.org/) instalado globalmente

### 📦 Instalar dependencias con Composer

Ejecuta el siguiente comando en la raíz del proyecto:

```bash
composer install
```

> 📁 Revisa la carpeta `versions/` para ver los cambios específicos por versión.  
> ⚠️ Si ya tenías una instalación previa, ejecuta las consultas necesarias si esta versión lo requiere.