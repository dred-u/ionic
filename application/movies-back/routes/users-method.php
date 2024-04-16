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

switch ($_SERVER['REQUEST_METHOD']) {

    //este es un get, trae datos
    case 'GET':
        // Consulta SQL para seleccionar datos de la tabla
        $sql = "SELECT id_usuario, nombre, email, fecha_creacion, ultimo_acceso FROM usuarios";

        $query = $conexion->query($sql);

        if ($query->num_rows > 0) {
            $data = array();
            while ($row = $query->fetch_assoc()) {
                $data[] = $row;
            }
            // Devolver los resultados en formato JSON
            header('Content-Type: application/json');
            echo json_encode($data);
        } else {
            echo "No se encontraron registros en la tabla.";
        }

        $conexion->close();
        break;

    //este es un post, crea registros nuevos
    case 'POST':
        function generateSessionToken($correo_electronico)
        {
            return md5($correo_electronico . uniqid());
        }

        // Leer el cuerpo JSON de la solicitud
        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data, true);

        // Verificar si se pudo decodificar el JSON correctamente
        if ($data !== null) {
            // Obtener los valores del arreglo asociativo
            $nombre = $data['nombre'];
            $correo_electronico = $data['email'];
            $contrasena = $data['password'];

            // Insertar los datos en la tabla, incluyendo el campo de último acceso
            $sql = "INSERT INTO usuarios (nombre, email, password, ultimo_acceso) 
                        VALUES ('$nombre', '$correo_electronico', '$contrasena', NOW())";

            // Ejecutar la consulta SQL
            if ($conexion->query($sql) === TRUE) {
                // Generar un token de sesión para el nuevo usuario
                $token = generateSessionToken($correo_electronico);

                // Insertar el token en la tabla de tokens
                $insertTokenSQL = "INSERT INTO token (email, token, estatus, fecha_creacion) 
                                       VALUES ('$correo_electronico', '$token', 'abierta', NOW())";
                if ($conexion->query($insertTokenSQL) === TRUE) {
                    // Consulta SQL para obtener información del usuario recién registrado
                    $sqlr = "SELECT id_usuario, nombre, email 
                                 FROM usuarios 
                                 WHERE email = '$correo_electronico' AND password = '$contrasena'";

                    // Ejecutar la consulta SQL
                    $result = $conexion->query($sqlr);
                    if ($result->num_rows > 0) {
                        $usuario = $result->fetch_assoc();
                        // Devolver el token y la información del usuario como respuesta en formato JSON
                        echo json_encode(array("token" => $token, "usuario" => $usuario));
                    } else {
                        echo json_encode(array("message" => "No se encontró el usuario recién registrado."));
                    }
                } else {
                    echo json_encode(array("message" => "Error al insertar el token en la base de datos"));
                }
            } else {
                echo json_encode(array("message" => "Error al insertar datos: " . $conexion->error));
            }
        } else {
            echo json_encode(array("message" => "Error al decodificar el JSON."));
        }


        // Cerrar la conexión a la base de datos
        $conexion->close();
        break;

    //este es un put
    case 'PUT':
        // Decodificar el JSON enviado en el cuerpo de la solicitud
        $data = json_decode(file_get_contents("php://input"), true);

        // Verificar si se proporcionó un ID de usuario en el cuerpo de la solicitud
        if (!isset($data['id_usuario'])) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(array("message" => "ID de usuario no proporcionado en el cuerpo de la solicitud"));
            exit();
        }

        // Obtener el ID del usuario
        $id_usuario = $data['id_usuario'];

        // Actualizar los datos en la base de datos
        $nombre = $data['nombre'];
        $correo_electronico = $data['correo_electronico'];
        $rol = $data['rol'];

        $sql = "UPDATE usuarios SET nombre='$nombre', correo_electronico='$correo_electronico', rol='$rol' WHERE id_usuario=$id_usuario";

        if ($conexion->query($sql) === TRUE) {
            // Devolver mensaje de éxito
            header("HTTP/1.1 200 OK");
            echo json_encode(array("message" => "Usuario actualizado correctamente"));
        } else {
            header("HTTP/1.1 500 Internal Server Error");
            echo json_encode(array("message" => "¡Upss eso no salió cómo esperábamos!: " . $conexion->error));
        }
        break;

    //Este es un patch, actualiza parcialmente el set de datos
    case 'PATCH':
        if ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
            parse_str(file_get_contents("php://input"), $datos);

            $id_usuario = $datos['id_usuario'];

            // Método PATCH
            $actualizaciones = array();
            if (!empty($datos['nombre'])) {
                $actualizaciones[] = "nombre = '{$datos['nombre']}'";
            }
            if (!empty($datos['correo_electronico'])) {
                $actualizaciones[] = "correo_electronico = '{$datos['correo_electronico']}'";
            }

            $actualizaciones_str = implode(', ', $actualizaciones);
            $sql = "UPDATE usuarios SET $actualizaciones_str WHERE id_usuario = $id_usuario";

            if ($conexion->query($sql) === TRUE) {
                echo "Registro actualizado con éxito.";
            } else {
                echo "¡Upss eso no salió cómo esperábamos!: " . $conexion->error;
            }
        }

        $conexion->close();
        break;

    case 'DELETE':
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            // Procesar solicitud DELETE
            $id_usuario = $_GET['id_usuario'];
            $sql = "DELETE FROM usuarios WHERE id_usuario = $id_usuario";
            if ($conexion->query($sql) === TRUE) {
                echo "Registro eliminado con éxito.";
            } else {
                echo "¡Upss eso no salió cómo esperábamos!: " . $conexion->error;
            }
        }
        $conexion->close();
        break;

    //options habilita a los desarrolladores obtener info del api y activa el cors
    case 'OPTIONS':
        // Habilitar CORS para cualquier origen
        header("Access-Control-Allow-Origin: *");

        // Permitir métodos HTTP específicos
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE, HEAD, TRACE, PATCH");

        // Permitir encabezados personalizados
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

        // Permitir credenciales
        header("Access-Control-Allow-Credentials: true");

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            // Responder a la solicitud OPTIONS sin procesar nada más
            http_response_code(200);
            exit;
        }
        break;

    case 'HEAD':
        if ($_SERVER['REQUEST_METHOD'] === 'HEAD') {
            // Establecer encabezados de respuesta
            header('Content-Type: application/json');
            header('Custom-Header: PHP 8, HTML ');

            // Puedes establecer otros encabezados necesarios aquí

            // No es necesario enviar un cuerpo en una solicitud HEAD, por lo que no se imprime nada aquí.
        } else {
            http_response_code(405); // Método no permitido
            echo 'Método de solicitud no válido';
        }
        break;

}
