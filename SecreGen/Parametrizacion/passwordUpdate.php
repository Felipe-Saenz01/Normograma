<?php
    if(!isset($_POST['password'])){
        header('Location: ../userInfo.php');
    }
    include '../../Models/conexion.php';

    $id = $_POST['id'];
    $password = $_POST['password'];
    //Encirptar clave
    $password_hash = password_hash($password, PASSWORD_BCRYPT);
    $sentencia = $db->prepare("UPDATE usuarios SET  clave = :clave WHERE codigo_Us = :codigo_Us;");
    $sentencia->bindParam(':clave',$password_hash, PDO::PARAM_STR);
    $sentencia->bindParam(':codigo_Us',$id, PDO::PARAM_INT);
    $resultado = $sentencia->execute();

    if($resultado === TRUE){
        $db= null;
        session_start();
        session_destroy();
        echo "
            <!DOCTYPE html>
            <html>
            <body>
        
            <form id='myForm' method='GET' action='../../login.php'>
            <input type='hidden' name='alertUserUpdate' value='2'><br>
            </form>
        
            <script>
            document.getElementById('myForm').submit();
            </script>
        
            </body>
            </html>
            ";
    }else{
        $db= null;
        header('location: ../../login.php');
    }



?>