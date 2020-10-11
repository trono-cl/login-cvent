<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Prueba</title>
</head>
<body>
    <h1>Formulario</h1>
    <form action="datos.php" method="POST">
        <label for="AccountNumber"> Numero de cuenta: </label>
        <input type="text" name="AccountNumber"><br><br>

        <label for="UserName"> Nombre: </label>
        <input type="text" name="UserName"><br><br>
        
        <label for="Password"> Contraseña:</label>
        <input type="text" name="Password"><br><br>

        <input type="submit" value="Enviar">
    </form>
</body>
</html>