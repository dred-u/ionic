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
        // Consulta SQL para seleccionar datos de la tabla
        $sql = "SELECT peliculas.id_pelicula, peliculas.titulo, peliculas.anno_estreno, peliculas.duracion_minutos, peliculas.imagen, genero.nombre AS genero 
                FROM peliculas 
                INNER JOIN genero ON peliculas.genero_id = genero.id_genero";

        $query = $conexion->query($sql);

        if ($query->num_rows > 0) {
            $data = array();
            while ($row = $query->fetch_assoc()) {
                // Convertir los valores numéricos a números
                $row['id_pelicula'] = (int) $row['id_pelicula'];
                $row['anno_estreno'] = (int) $row['anno_estreno'];
                $row['duracion_minutos'] = (int) $row['duracion_minutos'];
                $row['imagen'] = (string) $row['imagen'];
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
            if ($data && isset($data['titulo'], $data['anno_estreno'], $data['duracion_minutos'], $data['genero'], $data['imagen'])) {
                // Obtener valores
                $titulo = $data['titulo'];
                $anno_estreno = $data['anno_estreno'];
                $duracion_minutos = $data['duracion_minutos'];
                $genero_id = $data['genero'];
                $imagen = $data['imagen'];

                // Insertar los datos en la tabla
                $sql = "INSERT INTO peliculas (titulo, anno_estreno, duracion_minutos, genero_id, imagen) 
                VALUES ('$titulo', '$anno_estreno','$duracion_minutos', '$genero_id','$imagen')";

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
            if ($data && isset($data['id_pelicula'])) {
                $id_pelicula = $data['id_pelicula'];
                $titulo = isset($data['titulo']) ? $data['titulo'] : null;
                $genero_id = isset($data['genero_id']) ? $data['genero_id'] : null;
                $duracion_minutos = isset($data['duracion_minutos']) ? $data['duracion_minutos'] : null;
                $anno_estreno = isset($data['anno_estreno']) ? $data['anno_estreno'] : null;
                $imagen = isset($data['imagen']) ? $data['imagen'] : null;

                // Lógica de actualización
                $actualizaciones = array();
                if (!is_null($titulo)) {
                    $actualizaciones[] = "titulo = '$titulo'";
                }
                if (!is_null($genero_id)) {
                    $actualizaciones[] = "genero_id = '$genero_id'";
                }
                if (!is_null($duracion_minutos)) {
                    $actualizaciones[] = "duracion_minutos = '$duracion_minutos'";
                }
                if (!is_null($anno_estreno)) {
                    $actualizaciones[] = "anno_estreno = '$anno_estreno'";
                }
                if (!is_null($imagen)) {
                    $actualizaciones[] = "imagen = '$imagen'";
                }

                $actualizaciones_str = implode(', ', $actualizaciones);
                $sql = "UPDATE peliculas SET $actualizaciones_str WHERE id_pelicula = $id_pelicula";

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
            if ($data && isset($data['id_pelicula'], $data['titulo'], $data['anno_estreno'], $data['duracion_minutos'], $data['genero'], $data['imagen'])) {
                // Obtener valores
                $id_pelicula = $data['id_pelicula'];
                $titulo = $data['titulo'];
                $anno_estreno = $data['anno_estreno'];
                $duracion_minutos = $data['duracion_minutos'];
                $genero = $data['genero'];
                $imagen = $data['imagen'];

                // Lógica de actualización
                $sql = "UPDATE peliculas SET titulo = '$titulo', genero_id = '$genero', duracion_minutos = '$duracion_minutos', anno_estreno = '$anno_estreno', imagen = '$imagen'
                    WHERE id_pelicula = $id_pelicula";

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

            if ($data && isset($data['id_pelicula'])) {
                $id_pelicula = $data['id_pelicula'];

                // Procesar solicitud DELETE
                $sql = "DELETE FROM peliculas WHERE id_pelicula = $id_pelicula";

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

