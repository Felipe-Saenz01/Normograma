<?php

    if (isset($_POST['oculto'])){
        header('Location: Cuentas.php');
    }

    include '../../Models/conexion.php';

    $codigo = $_GET['id'];

    $sentencia = $db->prepare("DELETE FROM usuarios WHERE codigo_Us =?;");
    $resultado = $sentencia->execute([$codigo]);

    if($resultado === TRUE){
	$db= null;
        echo "
        <!DOCTYPE html>
        <html>
        <body>
    
        <form id='myForm' action='Cuentas.php'>
        <input type='hidden' name='alert1' value='2'><br>
        </form>
    
        <script>
        document.getElementById('myForm').submit();
        </script>
    
        </body>
        </html>
        ";
    }else{
	$db= null;
        echo "Error";
    }
?>
