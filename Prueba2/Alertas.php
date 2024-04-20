<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Productos</title>
    <link rel="stylesheet" href="estilo.css">
    <script src="productos.js"></script>
</head>
<body> 

    <!--Contenedor header -->
    <div class="contenedor-header">
        <header>
            <div class="logo">
                <a><span>ITIZ - UNCHAINED</span></a>
            </div>
            <div class="barra">
                <form onsubmit="buscarAlertas(); return false;">
                    <input type="text" placeholder="Buscar..." name="q" id="termino">
                    <button type="submit">Buscar</button>
                </form>
            </div>
            <nav id="nav" class="navegador">
                <ul class="menu">
                    <li><a href="Reportes.html" onclick="seleccionar()" class="texto">REPORTES</a></li>
                    <li><a href="Index.php" onclick="seleccionar()" class="texto">CERRAR SESION</a></li>                      
                </ul>
            </nav>
            <!-- Icono de hamburguesa -->
            <div class="nav-responsive" onclick="mostrarOcultarMenu()">
                <i class="fa-solid fa-bars"></i>
            </div>
            <!-- Icono de hamburguesa -->
        </header>
    </div>
    <!--Contenedor header -script -->
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

        // Función para realizar la búsqueda de forma asíncrona
        function buscarAlertas() {
            var termino = document.getElementById('termino').value;
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'Productos.php?q=' + termino, true);
            xhr.onload = function() {
                if (xhr.status >= 200 && xhr.status < 400) {
                    document.getElementById('resultados').innerHTML = xhr.responseText;
                } else {
                    console.error('Error al realizar la solicitud.');
                }
            };
            xhr.send();
        }
    </script>
    <!--Contenedor header -script -->

    <section class="Inicio">
        <div class="bodys">
            <div class="barra_lateral">
                <div class="botton">
                    <button class="primario">Fecha:</button>
                </div>
                <div class="calendario">
                    <input type="text" id="fechaInput">
                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
                    <script class="calenda">
                    // Inicializar el campo de entrada como un calendario emergente
                    $( function() {
                        $( "#fechaInput" ).datepicker();
                    });
                    </script>
                </div>
                <div class="botton">
                    <button class="primario">Gravedad</button>
                </div>
            </div>
            <div class="notificaciones">
                <!--Aqui-->
                <?php
                    // Crear la conexión a la base de datos
                    include_once("Conexion.php");
                    $conexion = new CConexion();
                    $conn = $conexion->ConecionBD();

                    // Consulta SQL para obtener todos los datos de la tabla 'alertas' y la columna 'gravedad'
                    $sql = "SELECT id, Hora, fecha, descripcion, gravedad FROM alertas";

                    // Ejecutar la consulta
                    $stmt = $conn->query($sql);

                    // Verificar si hay filas devueltas
                    if ($stmt->rowCount() > 0) {
                        echo "<div id='resultados'><table border='1'>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Hora</th>
                                        <th>Fecha</th>
                                        <th>Descripción</th>
                                        <th>Gravedad</th> <!-- Nueva columna agregada -->
                                    </tr>
                                </thead>
                                <tbody>";

                        // Recorrer los resultados y mostrar cada fila en la tabla
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>
                                    <td>{$row['id']}</td>
                                    <td>{$row['hora']}</td> <!-- Corregir 'Hora' con mayúscula -->
                                    <td>{$row['fecha']}</td>
                                    <td>{$row['descripcion']}</td>
                                    <td>{$row['gravedad']}</td> <!-- Mostrar el valor de la columna gravedad -->
                                </tr>";
                        }

                        echo "</tbody></table></div>";
                    } else {
                        echo "No se encontraron resultados.";
                    }

                    // Verificar si se ha enviado una solicitud de búsqueda
                    if (isset($_GET['q'])) {
                        // Obtener el término de búsqueda ingresado por el usuario
                        $termino_busqueda = $_GET['q'];

                        // Consulta SQL para obtener los datos de la tabla 'alertas' y la columna 'gravedad' con el término de búsqueda
                        $sql_busqueda = "SELECT id, Hora, fecha, descripcion, gravedad FROM alertas WHERE descripcion LIKE :termino";

                        // Preparar la consulta
                        $stmt_busqueda = $conn->prepare($sql_busqueda);

                        // Vincular el parámetro del término de búsqueda
                        $termino = "%$termino_busqueda%";
                        $stmt_busqueda->bindParam(':termino', $termino, PDO::PARAM_STR);

                        // Ejecutar la consulta de búsqueda
                        $stmt_busqueda->execute();

                        // Verificar si hay filas devueltas por la búsqueda
                        if ($stmt_busqueda->rowCount() > 0) {
                            echo "<div id='resultados_busqueda'><p>Resultados de la búsqueda para: $termino_busqueda</p><table border='1'>
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Hora</th>
                                            <th>Fecha</th>
                                            <th>Descripción</th>
                                            <th>Gravedad</th> <!-- Nueva columna agregada -->
                                        </tr>
                                    </thead>
                                    <tbody>";

                            // Recorrer los resultados de la búsqueda y mostrar cada fila en la tabla
                            while ($row = $stmt_busqueda->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr>
                                        <td>{$row['id']}</td>
                                        <td>{$row['hora']}</td> <!-- Corregir 'Hora' con mayúscula -->
                                        <td>{$row['fecha']}</td>
                                        <td>{$row['descripcion']}</td>
                                        <td>{$row['gravedad']}</td> <!-- Mostrar el valor de la columna gravedad -->
                                    </tr>";
                            }

                            echo "</tbody></table></div>";
                        } else {
                            echo "<div id='resultados_busqueda'>No se encontraron resultados para la búsqueda: $termino_busqueda.</div>";
                        }
                    }
                ?>
                <!--Aqui-->
            </div>
        </div>
    </section>
    
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
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Productos</title>
    <link rel="stylesheet" href="estilo.css">
    <script src="productos.js"></script>
</head>
<body> 


    <!--Contenedor header -->
    <div class="contenedor-header">
        <header>
            <div class="logo">
                <a><span>ITIZ - UNCHAINED</span></a>
            </div>
            <div class="barra">
                <form action="/buscar" method="get">
                    <input type="text" placeholder="Buscar..." name="q">
                    <button type="submit">Buscar</button>
                </form>
            </div>
            <nav id="nav" class="navegador">
                <ul class="menu">
                    <li><a href="Reportes.php" onclick="seleccionar()" class="texto">REPORTES</a></li>
                    <li><a href="Index.html" onclick="seleccionar()" class="texto">CERRAR SESION</a></li>                      
                </ul>
            </nav>
            <!-- Icono de hamburguesa -->
            <div class="nav-responsive" onclick="mostrarOcultarMenu()">
                <i class="fa-solid fa-bars"></i>
            </div>
            <!-- Icono de hamburguesa -->
        </header>
    </div>
    <!--Contenedor header -script -->
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

    <section class="Inicio">
        <div class="bodys">
        <div class="barra_lateral">

            <div class="botton">
                <button class="primario">Fecha:</button>
            </div>
            <div class="calendario">
                <input type="text" id="fechaInput">
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
                <script class="calenda">
                // Inicializar el campo de entrada como un calendario emergente
    $( function() {
      $( "#fechaInput" ).datepicker();
    });
            </script>
            </div>
            <div class="botton">
                <button class="primario">Gravedad</button>
            </div>
        </div>
  <div class="notificaciones">
    <!--Aqui-->
  <?php
    // Crear la conexión a la base de datos
    include_once("Conexion.php");
    $conexion = new CConexion();
    $conn = $conexion->ConecionBD();

    // Consulta SQL para obtener todos los datos de la tabla 'alertas' y la columna 'gravedad'
    $sql = "SELECT id, Hora, fecha, descripcion, gravedad FROM alertas";

    // Ejecutar la consulta
    $stmt = $conn->query($sql);

    // Verificar si hay filas devueltas
    if ($stmt->rowCount() > 0) {
        echo "<table border='1'>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Hora</th>
                        <th>Fecha</th>
                        <th>Descripción</th>
                        <th>Gravedad</th> <!-- Nueva columna agregada -->
                    </tr>
                </thead>
                <tbody>";

        // Recorrer los resultados y mostrar cada fila en la tabla
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['hora']}</td> <!-- Corregir 'Hora' con mayúscula -->
                    <td>{$row['fecha']}</td>
                    <td>{$row['descripcion']}</td>
                    <td>{$row['gravedad']}</td> <!-- Mostrar el valor de la columna gravedad -->
                </tr>";
        }

        echo "</tbody></table>";
    } else {
        echo "No se encontraron resultados.";
    }

    // Verificar si se ha enviado una solicitud de búsqueda
    if (isset($_GET['q'])) {
        // Obtener el término de búsqueda ingresado por el usuario
        $termino_busqueda = $_GET['q'];

        // Consulta SQL para obtener los datos de la tabla 'alertas' y la columna 'gravedad' con el término de búsqueda
        $sql_busqueda = "SELECT id, Hora, fecha, descripcion, gravedad FROM alertas WHERE descripcion LIKE :termino";

        // Preparar la consulta
        $stmt_busqueda = $conn->prepare($sql_busqueda);

        // Vincular el parámetro del término de búsqueda
        $termino = "%$termino_busqueda%";
        $stmt_busqueda->bindParam(':termino', $termino, PDO::PARAM_STR);

        // Ejecutar la consulta de búsqueda
        $stmt_busqueda->execute();

        // Verificar si hay filas devueltas por la búsqueda
        if ($stmt_busqueda->rowCount() > 0) {
            echo "<p>Resultados de la búsqueda para: $termino_busqueda</p>";
            echo "<table border='1'>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Hora</th>
                            <th>Fecha</th>
                            <th>Descripción</th>
                            <th>Gravedad</th> <!-- Nueva columna agregada -->
                        </tr>
                    </thead>
                    <tbody>";

            // Recorrer los resultados de la búsqueda y mostrar cada fila en la tabla
            while ($row = $stmt_busqueda->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['hora']}</td> <!-- Corregir 'Hora' con mayúscula -->
                        <td>{$row['fecha']}</td>
                        <td>{$row['descripcion']}</td>
                        <td>{$row['gravedad']}</td> <!-- Mostrar el valor de la columna gravedad -->
                    </tr>";
            }

            echo "</tbody></table>";
        } else {
            echo "No se encontraron resultados para la búsqueda: $termino_busqueda.";
        }
    }
?>


    <!--Aqui-->
</div>


        </div>
        </div>
    </section>
    
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
