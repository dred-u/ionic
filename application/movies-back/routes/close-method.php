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

// Obtener el JSON enviado en el cuerpo de la solicitud
$json_data = file_get_contents('php://input');

// Decodificar el JSON en un arreglo asociativo
$data = json_decode($json_data, true);

// Verificar si se pudo decodificar el JSON correctamente
if ($data !== null) {
    // Obtener el correo electrónico del arreglo asociativo
    $correo_electronico = $data['email'];

    // Verificar la conexión
    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }

    // Consulta SQL para verificar si el correo electrónico existe en la tabla token
    $sql = "SELECT * FROM token WHERE email = '$correo_electronico' AND estatus = 'abierta'";
    $result = $conexion->query($sql);

    // Verificar si se encontraron resultados
    if ($result->num_rows > 0) {
        // Cambiar el estatus del token a "cerrada"
        $updateTokenSQL = "UPDATE token SET estatus = 'cerrada' WHERE email = '$correo_electronico' AND estatus = 'abierta'";
        if ($conexion->query($updateTokenSQL) === TRUE) {
            echo json_encode(array("message" => "Estatus del token cambiado a 'cerrada'"));
        } else {
            echo json_encode(array("message" => "Error al cambiar el estatus del token"));
        }
    } else {
        // No se encontró el correo electrónico en la tabla token o ya tiene estatus "cerrada"
        echo json_encode(array("message" => "No se encontró un token abierto asociado al correo electrónico proporcionado"));
    }

    // Cerrar la conexión
    $conexion->close();
} else {
    // Error al decodificar el JSON
    echo json_encode(array("message" => "Error al decodificar el JSON"));
}
?>
