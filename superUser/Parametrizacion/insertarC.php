<?php
if(isset($_POST['oculto'])){
exit();
}

include '../../Models/conexion.php';

$nombre_usuario = $_POST['nombre']; //ok
$tipo_de_usuario = $_POST['tipo_de_usuario']; //ok
$numero_documento = $_POST['numero_documento'];//ok
$usuario = $_POST['usuario'];//ok
$clave = $_POST['clave'];//ok
$Estado = $_POST['Estado'];//ok

$password_hash = password_hash($clave, PASSWORD_BCRYPT);

/*$sentencia = $db->prepare("INSERT INTO usuarios(nombre, tipo_de_usuario, numero_documento,
    usuario, clave, Estado) VALUES (?,?,?,?,?,?);");*/

$query = $db->prepare("SELECT * FROM usuarios WHERE usuario=:usuario");

$query->bindParam("usuario",$usuario,PDO::PARAM_STR);

$query->execute();

if ($query->rowCount() > 0) {
    echo '<p class="error">El coreo ya esta registrado</p>';
}

if ($query->rowCount() == 0){
    $query =$db->prepare("INSERT INTO usuarios(nombre, tipo_de_usuario, numero_documento,
    usuario, clave, estado) VALUES (UPPER(:nombre_usuario),:tipo_de_usuario,:numero_documento,:usuario,:password_hash,:Estado)");

    $query->bindParam(":nombre_usuario", $nombre_usuario, PDO::PARAM_STR);
    $query->bindParam(":tipo_de_usuario", $tipo_de_usuario, PDO::PARAM_INT);
    $query->bindParam(":numero_documento", $numero_documento, PDO::PARAM_INT);
    $query->bindParam(":usuario", $usuario, PDO::PARAM_STR);
    $query->bindParam(":password_hash", $password_hash, PDO::PARAM_STR);
    $query->bindParam(":Estado", $Estado, PDO::PARAM_INT);

    $result = $query->execute();

    if($result === TRUE){
	$db= null;
        echo "
        <!DOCTYPE html>
        <html>
        <body>
    
        <form id='myForm' action='Cuentas.php'>
        <input type='hidden' name='alert' value='2'><br>
        </form>
    
        <script>
        document.getElementById('myForm').submit();
        </script>
    
        </body>
        </html>
        ";
    
    }else{
	$db= null;
        echo 'Error al insertar los datos';
    }
}


/*$resultado = $sentencia->execute([$nombre_usuario,$tipo_de_usuario,$numero_documento,$usuario,$clave,$Estado]);*/

/*if($resultado === TRUE){
    header('location: Cuentas.php');
}else{
    echo 'Error al insertar los datos';
}*/


?>
