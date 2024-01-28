<!DOCTYPE html>
<html>

<head>
    <title>Actualizar Registro</title>
</head>

<body>
    <h1>Actualizar Registro</h1>

    <form id="updateForm">
        <label for="id_pelicula">ID del Registro a Actualizar:</label>
        <input type="text" id="id_pelicula" name="id_pelicula" required><br>

        <label for="titulo">Nuevo titulo:</label>
        <input type="text" id="titulo" name="titulo"><br>

        <label for="genero">Nuevo genero:</label>
        <input type="text" id="genero" name="genero"><br>

        <label for="anno_estreno">Nuevo año:</label>
        <input type="text" id="anno_estreno" name="anno_estreno"><br>

        <label for="duracion_minutos">Nueva duracion:</label>
        <input type="text" id="duracion_minutos" name="duracion_minutos"><br>

        <button type="button" id="putButton">Actualizar con PUT</button>
        <button type="button" id="patchButton">Actualizar con PATCH</button>
    </form>

    <div id="response"></div>

    <script>
        document.getElementById('putButton').addEventListener('click', function () {
            actualizarRegistro('PUT');
        });

        document.getElementById('patchButton').addEventListener('click', function () {
            actualizarRegistro('PATCH');
        });

        function actualizarRegistro(metodo) {
            var id_pelicula = document.getElementById('id_pelicula').value;
            var titulo = document.getElementById('titulo').value;
            var genero = document.getElementById('genero').value;
            var anno_estreno = document.getElementById('anno_estreno').value;
            var duracion_minutos = document.getElementById('duracion_minutos').value;

            var data = {
                id_pelicula: id_pelicula,
                titulo: titulo,
                genero: genero,
                anno_estreno: anno_estreno,
                duracion_minutos: duracion_minutos
            };

            fetch('method.php', {
                method: metodo,
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
        }
    </script>
</body>

</html>