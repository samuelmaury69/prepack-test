<?php
include 'confing.php';
// Crear una conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error en la conexión a la base de datos: " . $conn->connect_error);
}

// Función para verificar las credenciales del usuario
function verificarCredenciales($conn, $nombreUsuario, $contrasena) {
    $sql = "SELECT id, nombre, email, password FROM usuarios WHERE nombre = '$nombreUsuario' OR email = '$nombreUsuario'";
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();  
        // if (password_verify($contrasena, $row["password"])) {
        if ( $contrasena==$row["password"]) {
            return $row;
        }
    }
    return null;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombreUsuario = $_POST["nombreUsuario"];
    $contrasena = $_POST["contrasena"];
    
    // Verificar las credenciales del usuario
    $usuario = verificarCredenciales($conn, $nombreUsuario, $contrasena);
    if ($usuario) {
        // Iniciar sesión
        $_SESSION["usuario_id"] = $usuario["id"];
        $_SESSION["usuario_nombre"] = $usuario["nombre"];
        header("Location: test.php"); // Redirigir a la página de inicio
        exit();
    } else {
        $mensajeError = "Credenciales incorrectas. Por favor, inténtalo de nuevo.";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Iniciar Sesión</title>
</head>
<body>

<h2>Iniciar Sesión</h2>

<?php if (isset($mensajeError)) { ?>
    <p style="color: red;"><?php echo $mensajeError; ?></p>
<?php } ?>

<form method="post" action="">
    <label for="nombreUsuario">Nombre de Usuario o Correo Electrónico:</label><br>
    <input type="text" id="nombreUsuario" name="nombreUsuario" required><br><br>

    <label for="contrasena">Contraseña:</label><br>
    <input type="password" id="contrasena" name="contrasena" required><br><br>

    <input type="submit" value="Iniciar Sesión">
</form>

</body>
</html>
