PROYECTO DE LOGIN POR ROLES - PHP + MariaDB (XAMPP)

IMPORTANTE:
- Es un proyecto sencillo para estudiar y adaptar. Revísalo y asegúrate de poder explicar cada archivo a tu profesor.
- Está preparado para la base de datos proyecto_roles en XAMPP/MariaDB puerto 3307.
- No reemplaza todos los entregables del curso (scripts SQL de semanas 1-4, diagrama E-R, tabla de pruebas y presentación).

INSTALACIÓN
1. Copia la carpeta "proyecto" dentro de C:\xampp\htdocs\
   Debe quedar C:\xampp\htdocs\proyecto\
2. Inicia Apache y MySQL en XAMPP.
3. Abre config.php y revisa DB_HOST, DB_PORT, DB_NAME, DB_USER y DB_PASS.
   Esta versión usa root sin contraseña y puerto 3307, como en tu configuración actual.
4. Abre en el navegador:
   http://localhost/proyecto/setup.php
   Esto crea el usuario inicial AlanT si todavía no existe, con contraseña protegida usando password_hash().
5. Cuando aparezca el mensaje de éxito, elimina setup.php de la carpeta por seguridad.
6. Abre http://localhost/proyecto/login.php
   Usuario: AlanT
   Contraseña inicial: alantnunez
7. Prueba cerrar sesión y registrar un usuario nuevo desde "Crear cuenta". El registro público crea usuarios con rol empleado solamente.
8. El admin puede crear usuarios y asignar roles desde su menú.

ROLES
- admin: puede crear usuarios y ver auditoría.
- propietario: puede ver el resumen del negocio.
- empleado: puede registrar una venta sencilla de demostración.

NOTAS
- La base de datos debe tener las tablas rol, usuario y auditoria_acceso con columnas compatibles con el script del profesor.
- El proyecto usa PDO, consultas preparadas, password_hash/password_verify, sesiones y control de rol en cada página.
- En tu proyecto real, la parte de ventas/productos debe integrarse con las tablas de negocio diseñadas por tu grupo.
