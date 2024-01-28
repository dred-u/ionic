<!DOCTYPE html>
<html>

<head>
    <title>API de Ejemplo (GET, POST, PUT, DELETE)</title>
    <script src="min.js"></script>

</head>

<body>
    <h1>Eliminar Registro por ID</h1>

    <form id="deleteForm">
        <label for="id_pelicula">ID del Registro a Eliminar:</label>
        <input type="text" id="id_pelicula" name="id_pelicula" required>
        <button type="button" id="deleteButton">Eliminar</button>
    </form>

    <div id="response"></div>

    <script>
        // Agregar un evento al botón para enviar la solicitud DELETE
        document.getElementById('deleteButton').addEventListener('click', function () {
            var id_pelicula = document.getElementById('id_pelicula').value;

            var data = {
                id_pelicula: id_pelicula
            };

            fetch('method.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
                .then(function (response) {
                    return response.text();
                })
                .then(function (data) {
                    document.getElementById('response').textContent = data;
                })
                .catch(function (error) {
                    console.error('Error:', error);
                });
        });
    </script>

</body>

</html>