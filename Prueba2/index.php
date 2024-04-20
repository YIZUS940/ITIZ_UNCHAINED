<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Iniciar Sesión</title>

    <link rel="stylesheet" href="estilo.css">
    <script src="iniciodessesion.js"></script>
</head>
<body>
<?php
// Lógica de búsqueda en la base de datos
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["Checar"])) {
    // Obtener los datos del formulario
    $nombre = $_POST["nombre"];
    $password = $_POST["password"];

    // Crear la conexión a la base de datos
    include_once("Conexion.php");
    $conexion = new CConexion();
    $conn = $conexion->ConecionBD();

    // Consulta SQL de búsqueda
    $sql = "SELECT * FROM usuario WHERE nombre = :nombre AND password = :password";

    // Preparar la consulta
    $stmt = $conn->prepare($sql);

    // Vincular parámetros
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':password', $password);

    // Ejecutar la consulta
    try {
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($resultado) {
            header("Location: Alertas.php");
        } else {
            echo "<script>alert('Los datos son incorrectos.');</script>";

            // Envío de correo electrónico
            $destinatario = "fermeza070901@gmail.com";
            $asunto = "Intento de inicio de sesión fallido";
            $mensaje = "Se ha intentado iniciar sesión en el sistema con los siguientes datos:
                        Nombre de usuario: $nombre
                        Contraseña: $password";
            $cabeceras = "From: fermeza070901@gmail.com";

            // Enviar correo electrónico
            if (mail($destinatario, $asunto, $mensaje, $cabeceras)) {
                echo "<script>alert('Correo electrónico enviado con éxito.');</script>";
            } else {
                echo "<script>alert('Error al enviar el correo electrónico.');</script>";
            }

            // Obtener la hora y fecha actuales
            $hora_actual = date("H:i:s");
            $fecha_actual = date("Y-m-d");

            // Insertar la alerta en la base de datos
            $sql_insert = "INSERT INTO alertas (Hora, fecha, descripcion, Gravedad) VALUES (:hora_actual, :fecha_actual, 'Intento de inicio de sesión fallido', CASE WHEN random() < 0.33 THEN 'Alta' WHEN random() < 0.66 THEN 'Media' ELSE 'Baja' END)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bindParam(':hora_actual', $hora_actual);
            $stmt_insert->bindParam(':fecha_actual', $fecha_actual);
            $stmt_insert->execute();
        }
    } catch (PDOException $exp) {
        echo "Error al buscar datos en la base de datos: " . $exp->getMessage();
    }
}
?>

    <!--Contenedor header -->
<div class="contenedor-header">
    <header>
        <div class="logo">
            <a href="index.html">
                <span>ITIZ - UNCHAINED</span>                    
            </a>
        </div>
        <!-- Icono de hamburguesa -->
        <div class="nav-responsive" onclick="mostrarOcultarMenu()">
            <i class="fa-solid fa-bars"></i>
        </div>
        <!-- Icono de hamburguesa -->
    </header>
</div>
    <!--Contenedor header -->
    <script>
       // Función para mostrar u ocultar el menú en pantallas pequeñas
    function mostrarOcultarMenu() {
        var menu = document.querySelector('.contenedor-header .navegador .menu');
        menu.classList.toggle('active');
    }

    // Función para ocultar el menú en pantallas pequeñas después de hacer clic en un enlace
    function seleccionar() {
        var menu = document.querySelector('.contenedor-header .navegador .menu');
        menu.classList.remove('active');
    }

    // Función para mostrar u ocultar el submenú
    function mostrarSubMenu(menuId) {
        var subMenu = document.getElementById(menuId + 'SubMenu');
        if (subMenu) {
            subMenu.classList.toggle('active');
        }
    }
    </script>
    <!--Contenedor header -script -->
    
    <!-- Inicio -->
    <section id="Inicio" class="Inicio">
        <div class="container">
            <h2>Iniciar Sesión</h2>
            <form id="loginForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" >
                <input type="text" class="form-control" id="username" name="nombre" placeholder="Nombre de usuario" required>
                <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required>
                <input type="submit" name="Checar"  value="Iniciar Sesión">
            </form>
        </div>
    </section>
    <!-- Fin del contenido principal -->

    <!--FOOTER-->
    <footer>
        <div class="redes">
            <!-- <a href="https://www.facebook.com/groups/428074003141405"><i class="fa-brands fa-facebook-f"></i></a> -->
            <div class="Ad">
                <p>Created by UNCHAINED 2024 | Para cualquier problema, contáctanos al teléfono: 5634579955</p> 
            </div>
        </div>
    </footer>
</body>
</html>