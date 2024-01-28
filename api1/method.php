<?php
require "config/Conexion.php";

// Verificar el método de solicitud
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Consulta SQL para seleccionar datos de la tabla
        $sql = "SELECT id_pelicula, titulo, anno_estreno, duracion_minutos, genero FROM peliculas";

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
        break;

    case 'POST':
        // Verificar si es una solicitud POST
        if ($method === 'POST') {
            // Obtener datos del cuerpo de la solicitud
            $json_data = file_get_contents("php://input");
            $data = json_decode($json_data, true);

            // Verificar si se recibieron datos válidos
            if ($data && isset($data['titulo'], $data['anno_estreno'], $data['duracion_minutos'], $data['genero'])) {
                // Obtener valores
                $titulo = $data['titulo'];
                $anno_estreno = $data['anno_estreno'];
                $duracion_minutos = $data['duracion_minutos'];
                $genero = $data['genero'];

                // Insertar los datos en la tabla
                $sql = "INSERT INTO peliculas (titulo, anno_estreno, duracion_minutos, genero) VALUES ('$titulo', '$anno_estreno','$duracion_minutos', '$genero')";

                if ($conexion->query($sql) === TRUE) {
                    echo "Datos insertados con éxito.";
                } else {
                    echo "Error al insertar datos: " . $conexion->error;
                }
            } else {
                echo "Datos inválidos o incompletos en la solicitud JSON.";
            }
        } else {
            echo "Esta API solo admite solicitudes POST.";
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
                $genero = isset($data['genero']) ? $data['genero'] : null;
                $duracion_minutos = isset($data['duracion_minutos']) ? $data['duracion_minutos'] : null;
                $anno_estreno = isset($data['anno_estreno']) ? $data['anno_estreno'] : null;

                // Lógica de actualización
                $actualizaciones = array();
                if (!is_null($titulo)) {
                    $actualizaciones[] = "titulo = '$titulo'";
                }
                if (!is_null($genero)) {
                    $actualizaciones[] = "genero = '$genero'";
                }
                if (!is_null($duracion_minutos)) {
                    $actualizaciones[] = "duracion_minutos = '$duracion_minutos'";
                }
                if (!is_null($anno_estreno)) {
                    $actualizaciones[] = "anno_estreno = '$anno_estreno'";
                }

                $actualizaciones_str = implode(', ', $actualizaciones);
                $sql = "UPDATE peliculas SET $actualizaciones_str WHERE id_pelicula = $id_pelicula";

                if ($conexion->query($sql) === TRUE) {
                    echo "Registro actualizado con éxito.";
                } else {
                    echo "Error al actualizar registro: " . $conexion->error;
                }
            } else {
                echo "Datos inválidos o incompletos en la solicitud JSON.";
            }
        } else {
            echo "Método de solicitud no válido.";
        }
        break;

    case 'PUT':
        // Verificar si es una solicitud PUT
        if ($method === 'PUT') {
            // Obtener datos del cuerpo de la solicitud
            $json_data = file_get_contents("php://input");
            $data = json_decode($json_data, true);

            // Verificar si se recibieron datos válidos
            if ($data && isset($data['id_pelicula'], $data['titulo'], $data['anno_estreno'], $data['duracion_minutos'], $data['genero'])) {
                // Obtener valores
                $id_pelicula = $data['id_pelicula'];
                $titulo = $data['titulo'];
                $anno_estreno = $data['anno_estreno'];
                $duracion_minutos = $data['duracion_minutos'];
                $genero = $data['genero'];

                // Lógica de actualización
                $sql = "UPDATE peliculas SET titulo = '$titulo', genero = '$genero', duracion_minutos = '$duracion_minutos', anno_estreno = '$anno_estreno'  WHERE id_pelicula = $id_pelicula";

                if ($conexion->query($sql) === TRUE) {
                    echo "Registro actualizado con éxito.";
                } else {
                    echo "Error al actualizar registro: " . $conexion->error;
                }
            } else {
                echo "Datos inválidos o incompletos en la solicitud JSON.";
            }
        } else {
            echo "Método de solicitud no válido.";
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
                    echo "Registro eliminado con éxito.";
                } else {
                    echo "Error al eliminar registro: " . $conexion->error;
                }
            } else {
                echo "Datos inválidos o incompletos en la solicitud JSON.";
            }
        } else {
            echo "Método de solicitud no válido.";
        }
        break;

    default:
        echo 'undefined request type!';
}

// Cerrar conexión
$conexion->close();
?>
