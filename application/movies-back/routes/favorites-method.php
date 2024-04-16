<?php
require "config/Conexion.php";

header("Access-Control-Allow-Origin: *");

// Permitir métodos HTTP específicos
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE, HEAD, TRACE, PATCH");

// Permitir encabezados personalizados
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

// Permitir credenciales
header("Access-Control-Allow-Credentials: true");

// Verificar el método de solicitud
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Obtener la cadena de consulta de la URL
        $query_string = $_SERVER['QUERY_STRING'];

        // Convertir la cadena de consulta en un array asociativo
        parse_str($query_string, $params);

        // Extraer el valor del parámetro "usuario_id"
        $id_usuario = $params['id_usuario'];
        // Consulta SQL para seleccionar datos de la tabla
        $sql = "SELECT favoritas.id_favorita, favoritas.id_pelicula, favoritas.id_usuario, 
                       favoritas.calificacion, peliculas.titulo, peliculas.anno_estreno, 
                       peliculas.duracion_minutos, peliculas.imagen, genero.nombre AS genero
            FROM favoritas 
            INNER JOIN peliculas ON favoritas.id_pelicula = peliculas.id_pelicula
            INNER JOIN genero ON peliculas.genero_id = genero.id_genero
            WHERE favoritas.id_usuario = $id_usuario";

        $query = $conexion->query($sql);

        if ($query->num_rows > 0) {
            $data = array();
            while ($row = $query->fetch_assoc()) {
                // Convertir los valores numéricos a números
                $row['id_favorita'] = (int) $row['id_favorita'];
                $row['id_pelicula'] = (int) $row['id_pelicula'];
                $row['id_usuario'] = (int) $row['id_usuario'];
                $row['calificacion'] = (int) $row['calificacion'];
                $data[] = $row;
            }
            // Devolver los resultados en formato JSON
            echo json_encode($data);
        } else {
            echo json_encode(array("message" => "No se encontraron registros en la tabla."));
        }
        break;


        case 'POST':
            // Verificar si es una solicitud POST
            if ($method === 'POST') {
                // Obtener datos del cuerpo de la solicitud
                $json_data = file_get_contents("php://input");
                $data = json_decode($json_data, true);
    
                // Verificar si se recibieron datos válidos
                if ($data && isset($data['id_usuario'], $data['id_pelicula'])){
                    // Obtener valores
                    $id_usuario = $data['id_usuario'];
                    $id_pelicula = $data['id_pelicula'];

    
                    // Insertar los datos en la tabla
                    $sql = "INSERT INTO favoritas (id_usuario, id_pelicula) 
                    VALUES ('$id_usuario', '$id_pelicula')";
    
                    if ($conexion->query($sql) === TRUE) {
                        echo json_encode(array("message" => "Datos insertados con éxito."));
                    } else {
                        echo json_encode(array("message" => "Error al insertar datos: " . $conexion->error));
                    }
                } else {
                    echo json_encode(array("message" => "Datos inválidos o incompletos en la solicitud JSON."));
                }
            } else {
                echo json_encode(array("message" => "Esta API solo admite solicitudes POST."));
            }
            break;
    
        case 'PATCH':
            // Verificar si es una solicitud PATCH
            if ($method === 'PATCH') {
                // Obtener datos del cuerpo de la solicitud
                $json_data = file_get_contents("php://input");
                $data = json_decode($json_data, true);
    
                // Verificar si se recibieron datos válidos
                if ($data && isset($data['id_favorita '])) {
                    $id_favorita  = $data['id_favorita '];
                    $id_usuario = isset($data['id_usuario']) ? $data['id_usuario'] : null;
                    $id_pelicula = isset($data['id_pelicula']) ? $data['id_pelicula'] : null;
                    $calificacion = isset($data['calificacion']) ? $data['calificacion'] : null;
    
                    // Lógica de actualización
                    $actualizaciones = array();
                    if (!is_null($id_usuario)) {
                        $actualizaciones[] = "id_usuario = '$id_usuario'";
                    }
                    if (!is_null($id_pelicula)) {
                        $actualizaciones[] = "id_pelicula = '$id_pelicula'";
                    }
                    if (!is_null($calificacion)) {
                        $actualizaciones[] = "calificacion = '$calificacion'";
                    }
    
                    $actualizaciones_str = implode(', ', $actualizaciones);
                    $sql = "UPDATE favoritas SET $actualizaciones_str WHERE id_favorita  = $id_favorita ";
    
                    if ($conexion->query($sql) === TRUE) {
                        echo json_encode(array("message" => "Registro actualizado con éxito."));
                    } else {
                        echo json_encode(array("message" => "Error al actualizar registro: " . $conexion->error));
                    }
                } else {
                    echo json_encode(array("message" => "Datos inválidos o incompletos en la solicitud JSON."));
                }
            } else {
                echo json_encode(array("message" => "Método de solicitud no válido."));
            }
            break;
    
            case 'PUT':
                // Verificar si es una solicitud PUT
                if ($method === 'PUT') {
                    // Obtener datos del cuerpo de la solicitud
                    $json_data = file_get_contents("php://input");
                    $data = json_decode($json_data, true);
            
                    // Verificar si se recibieron datos válidos
                    if ($data && isset($data['id_favorita '], $data['id_usuario'], $data['id_pelicula'], $data['calificacion']))  {
                        // Obtener valores
                        $id_favorita  = $data['id_favorita '];
                        $id_usuario = $data['id_usuario'];
                        $id_pelicula = $data['id_pelicula'];
                        $calificacion = $data['calificacion'];
    
                        // Lógica de actualización
                        $sql = "UPDATE favoritas SET id_usuario = '$id_usuario', id_pelicula = '$id_pelicula', calificacion = '$calificacion'
                        WHERE id_favorita  = $id_favorita ";
            
                        if ($conexion->query($sql) === TRUE) {
                            echo json_encode(array("message" => "Registro actualizado con éxito."));
                        } else {
                            echo json_encode(array("message" => "Error al actualizar registro: " . $conexion->error));
                        }
                    } else {
                        echo json_encode(array("message" => "Datos inválidos o incompletos en la solicitud JSON."));
                    }
                } else {
                    echo json_encode(array("message" => "Método de solicitud no válido."));
                }
                break;
    
        case 'DELETE':
            // Verificar si es una solicitud DELETE
            if ($method === 'DELETE') {
                // Obtener el ID de la película de la solicitud
                $json_data = file_get_contents("php://input");
                $data = json_decode($json_data, true);
    
                if ($data && isset($data['id_favorita'])) {
                    $id_favorita  = $data['id_favorita'];
    
                    // Procesar solicitud DELETE
                    $sql = "DELETE FROM favoritas WHERE id_favorita  = $id_favorita ";
    
                    if ($conexion->query($sql) === TRUE) {
                        echo json_encode(array("message" => "Registro eliminado con éxito."));
                    } else {
                        echo json_encode(array("message" => "Error al eliminar registro: " . $conexion->error));
                    }
                } else {
                    echo json_encode(array("message" => "Datos inválidos o incompletos en la solicitud JSON."));
                }
            } else {
                echo json_encode(array("message" => "Método de solicitud no válido."));
            }
            break;
    
        default:
            echo json_encode(array("message" => "Método de solicitud no válido."));
    }
    
    // Cerrar conexión
    $conexion->close();
    
    