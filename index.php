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
        <input type="text" name="AccountNumber" value="CVENTCS714"><br><br>

        <label for="UserName"> Nombre: </label>
        <input type="text" name="UserName" value="CVENTCS714API" ><br><br>
        
        <label for="Password"> Contraseña:</label>
        <input type="text" name="Password" value="Redalert1."><br><br>

        <label for="StartDate"> StartDate:</label>
        <input type="text" name="StartDate" value="2020-10-07T00:00:00"><br><br>

        <label for="EndDate"> EndDate:</label>
        <input type="text" name="EndDate" value="2020-10-08T23:59:00"><br><br>

        <input type="submit" value="Enviar">
    </form>
</body>
</html>
