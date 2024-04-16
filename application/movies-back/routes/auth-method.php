<?php
require "config/Conexion.php";

// Habilitar CORS para cualquier origen
header("Access-Control-Allow-Origin: *");

// Permitir métodos HTTP específicos
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE, HEAD, TRACE, PATCH");

// Permitir encabezados personalizados
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

// Permitir credenciales
header("Access-Control-Allow-Credentials: true");

// Función para generar un token de sesión
function generateSessionToken($correo_electronico) {
    return md5($correo_electronico . uniqid());
}

// Obtener el JSON enviado en el cuerpo de la solicitud
$json_data = file_get_contents('php://input');

// Decodificar el JSON en un arreglo asociativo
$data = json_decode($json_data, true);

// Verificar si se pudo decodificar el JSON correctamente
if ($data !== null) {
    // Obtener los valores del arreglo asociativo
    $correo_electronico = $data['email'];
    $contrasena = $data['password'];

    // Verificar la conexión
    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }

    // Consulta SQL para verificar si el usuario existe
    $sql = "SELECT id_usuario, nombre, email, ultimo_acceso FROM usuarios WHERE email = '$correo_electronico' AND password = '$contrasena'";

    $result = $conexion->query($sql);

    // Verificar si se encontraron resultados
    if ($result->num_rows > 0) {
        // Usuario encontrado
        $usuario = $result->fetch_assoc();
        $token = generateSessionToken($correo_electronico);
        
        // Actualizar el campo 'ultimo_acceso' en la tabla usuarios
        $updateUltimoAccesoSQL = "UPDATE usuarios SET ultimo_acceso = NOW() WHERE email = '$correo_electronico'";
        $conexion->query($updateUltimoAccesoSQL);
        
        // Insertar el token y la fecha en la tabla de tokens
        $insertTokenSQL = "INSERT INTO token (email, token, estatus, fecha_creacion) VALUES ('$correo_electronico', '$token', 'abierta', NOW())";
        if ($conexion->query($insertTokenSQL) === TRUE) {
            // Devolver el token y los datos del usuario como respuesta en formato JSON
            echo json_encode(array("token" => $token, "usuario" => $usuario));
        } else {
            echo json_encode(array("message" => "Error al insertar el token en la base de datos"));
        }
    } else {
        // Usuario no encontrado
        echo json_encode(array("message" => "Usuario no encontrado"));
    }

    // Cerrar la conexión
    $conexion->close();
} else {
    // Error al decodificar el JSON
    echo json_encode(array("message" => "Error al decodificar el JSON"));
}
?>
