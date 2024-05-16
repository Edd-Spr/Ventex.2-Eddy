<?php
// Inicia la sesión para poder usar variables de sesión
session_start();

// Requiere el archivo de conexión a la base de datos
require('../Conexion_db/conexion_usser_select.php');

// Verifica si están presentes los datos del formulario
if (!isset($_POST['correo'], $_POST['contrasena'])) {
    // Redirige a la página de inicio si falta algún dato
    header('Location: Incios.html');
    exit;
}

// Escapa los caracteres especiales en el correo y la contraseña para prevenir inyección SQL
$correo = mysqli_real_escape_string($Conexion_usser_select, $_POST['correo']);
$contra = mysqli_real_escape_string($Conexion_usser_select, $_POST['contrasena']);

// Prepara una consulta SQL para seleccionar el usuario con el correo proporcionado
if ($Result = $Conexion_usser_select->prepare('SELECT ID_Usuario, Nombre_Us, Correo, Pass, Fecha_Nac, telefono, Imagen, Type_usser FROM usuarioregistrado WHERE Correo = ?')) {
    $Result->bind_param('s', $correo); // Asocia el parámetro con el valor y ejecuta la consulta
    $Result->execute();
} else {
    die('Error en la preparación de la consulta'); // Termina el script si hay un error en la preparación de la consulta
}

// Almacena el resultado de la consulta
$Result->store_result();

// Verifica si se encontró algún usuario con el correo proporcionado
if ($Result->num_rows > 0) {
    // Obtiene los datos del usuario
    $Result->bind_result($id, $name, $email, $hash_password, $birthdate, $phone, $img, $type_usser);
    $Result->fetch();

    // Verifica si la contraseña proporcionada coincide con la contraseña almacenada en la base de datos
    if (password_verify($contra, $hash_password)) {

        echo $type_usser;
        $type_usser = intval($type_usser); 
        if(!$type_usser){}
        elseif($type_usser === 1) {
            // Regenera el ID de sesión para evitar ataques de fijación de sesiones
            session_regenerate_id();
            // Establece variables de sesión para indicar que el usuario está autenticado
            $_SESSION['Admin'] = TRUE;
            $_SESSION['name'] = $name;
            $_SESSION['birthdate'] = $birthdate;
            $_SESSION['phone'] = $phone;
            $_SESSION['id'] = $id;
            $_SESSION['email'] = $email;
            $_SESSION['img'] = $img;
            $_SESSION['Type'] = $type_usser;
            echo'1';
            header('Location: ..\..\Frames\Admin-Commen-Prod.php');
        } elseif ($type_usser === 2) {
            // Regenera el ID de sesión para evitar ataques de fijación de sesiones
            session_regenerate_id();
            // Establece variables de sesión para indicar que el usuario está autenticado
            $_SESSION['Usser'] = TRUE;
            $_SESSION['name'] = $name;
            $_SESSION['birthdate'] = $birthdate;
            $_SESSION['phone'] = $phone;
            $_SESSION['id'] = $id;
            $_SESSION['email'] = $email;
            $_SESSION['img'] = $img;
            $_SESSION['Type'] = $type_usser;
            echo'2';
            header('Location: ..\..\Frames\pantalla-perfil.php');
        } elseif ($type_usser === 3) {
            // Regenera el ID de sesión para evitar ataques de fijación de sesiones
            session_regenerate_id();
            // Establece variables de sesión para indicar que el usuario está autenticado
            $_SESSION['Usser'] = TRUE;
            $_SESSION['name'] = $name;
            $_SESSION['birthdate'] = $birthdate;
            $_SESSION['phone'] = $phone;
            $_SESSION['id'] = $id;
            $_SESSION['email'] = $email;
            $_SESSION['img'] = $img;
            $_SESSION['Type'] = $type_usser;
            echo'3';
            header('Location: ..\..\Frames\Pantalla-Perfil-Vip.php');
        }

        // Redirige al perfil del usuario
        //header('Location: ..\..\Frames\pantalla-perfil.php');
        exit;
    } else {
        // Redirige a la página de inicio con un mensaje de error si la contraseña es incorrecta
        header('Location: ..\..\Frames\pantalla-Login.html');
        exit;
    }
} else {
    // Redirige a la página de inicio con un mensaje de error si no se encontró ningún usuario con el correo proporcionado

    header('Location: ..\..\Frames\pantalla-Login.html');
    exit;
}

// Cierra el resultado y la conexión a la base de datos
$Result->close();
$Conexion_usser_select->close();
