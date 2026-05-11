<?php
$servername = "localhost";
$username = "username";
$password = "";
$dbname = "registro_retro_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";


/* No saber si colocar $sql= create tabla*/

/*if ($conn->query($sql) === TRUE) {
    echo "✅ Tabla 'usuarios' creada correctamente (o ya existía).";
} else {
    echo "❌ Error al crear la tabla: " . $conn->error;
}
*/
$conn->close();

?>