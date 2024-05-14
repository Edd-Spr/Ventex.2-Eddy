<?php
require_once('../php-servicios/Conexion_db/conexion_usser_select.php');
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ventex</title>
    <link rel="stylesheet" href="../Styles/Styles-Add-Prod.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cabin&family=Cabin+Sketch&family=Hammersmith+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cabin&family=Cabin+Sketch&family=Hammersmith+One&family=Hind:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <!-- Barra de navegación -->
    <nav id="nav">
        <!-- Título de la página -->
        <h1 id="name">Ventex</h1>
        <!-- Botones de navegación -->
        <button class="buttonP">Inicio</button>
        <button class="buttonP">Categoría</button>
        <button class="buttonP">Planes</button>
        <!-- Campo de búsqueda -->
        <input type="search" name="" id="search">
        <!-- Sección para mostrar fotos -->
        <section id="photo"></section>
    </nav>

    <!-- Contenido principal -->
    <section id="main">
        <!-- Columna lateral 1 -->
        <section id="side1">
            <!-- Título de sección -->
            <h1 id="Descrip-AgregarP">Agregar Producto</h1>
        </section>

        <!-- Columna lateral 2 -->
        <section id="side2">
            <!-- Formulario para agregar nuevo producto -->
            <form action="..\php-servicios\save_data\save-regristrer-new-product.php" id="form_product" method="post" enctype="multipart/form-data">
                <br>
                <!-- Campo de entrada para el nombre del producto -->
                <div class="inputbox" style="height: 5vh;">
                    <input type="text" name="product_name" class="inp" placeholder=" " required><br>
                    <!-- Etiqueta para describir el campo -->
                    <span class="text_input">Nombre del producto</span>
                </div>
                <div class="inputbox" style="height: 5vh;">
                    <input type="number" name="product_price" class="inp" placeholder=" " min="0" required><br>
                    <!-- Etiqueta para describir el campo -->
                    <span class="text_input">Precio</span>
                </div>
                <!-- Campo de entrada para la descripción del producto -->
                <div class="inputbox">
                    <input type="text" name="product_description" class="inp" placeholder=" " required><br>
                    <!-- Etiqueta para describir el campo -->
                    <span class="text_input">Descripción</span>
                </div>
                <!-- Contenedor para los selectores de categoría y subcategoría -->
                <div id="contaner-selects">
                    <!-- Selector de categoría -->
                    <select name="product_category" id="product_category" class="selects" require>
                        <option value="">Categoría</option>
                        <?php
                        // Consulta SQL para obtener todas las categorías
                        $sql_categorias = "SELECT DISTINCT Nombre_Cat FROM categoria";
                        $result_categorias = mysqli_query($Conexion_usser_select, $sql_categorias);

                        // Verificar si se obtuvieron resultados
                        if ($result_categorias && mysqli_num_rows($result_categorias) > 0) {
                            // Recorrer los resultados y mostrar cada categoría como una opción en el selector
                            while ($row = mysqli_fetch_assoc($result_categorias)) {
                                echo '<option value="' . $row['Nombre_Cat'] . '">' . $row['Nombre_Cat'] . '</option>';
                            }
                        }
                        ?>
                    </select>
                    <!-- Selector de subcategoría -->
                    <select name="product_subcategory" id="product_subcategory" require class="selects" style="margin-left: 10%;">
                        <option value="">Subcategoría</option>
                    </select>
                </div>
                    <!-- Sección para subir una foto del producto -->
                    <div id="up-photo">
                        <label for="archivo" class="custom-file-input">Añadir Imagen
                            <input type="file" name="product_image" id="archivo">
                        </label>
                    </div>
                    <!-- Botón para enviar el formulario -->
                    <div id="button-div">
                        <button type="submit" id="button-sumit">Publicar Producto</button>
                    </div>
            </form>
        </section>
    </section>

    <!-- Pie de página -->
    <footer id="footler-v">
        <!-- Título del pie de página -->
        <h1 id="name-footer">Ventex</h1>
    </footer>
    <script>
        $(document).ready(function() {
            // Manejar el cambio en el primer selector de categoría
            $('#product_category').change(function() {
                // Realizar una solicitud AJAX para obtener las subcategorías de la categoría seleccionada
                $.ajax({
                    url: '../php-servicios/load_data/load-subcategoria-pantallas-Edit-Add-Producto.php',
                    type: 'POST',
                    dataType: 'json',
                    data: $('#form_product').serialize(), // Serializar los datos del formulario
                    success: function(response) {
                        // Limpiar el selector de subcategorías
                        $('#product_subcategory').empty();
                        console.log('#product_subcategory')
                        // Agregar una opción por defecto
                        $('#product_subcategory').append($('<option>', {
                            value: '',
                            text: 'Subcategoría'
                        }));

                        // Agregar las subcategorías obtenidas al selector
                        $.each(response, function(index, subcategoria) {
                            $('#product_subcategory').append($('<option>', {
                                value: subcategoria,
                                text: subcategoria
                            }));
                        });
                    }
                });
            });
        });
    </script>
</body>

</html>