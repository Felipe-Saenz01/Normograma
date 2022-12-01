<?php
    $file = $_GET['ruta'];
    $id= $_GET['id'];
if(is_file($file)){
    chmod($file, 07777);
    if(!unlink($file)){
	echo "
        <!DOCTYPE html>
        <html>
        <body>
    
        <form id='myForm' action='superUser.php'>
        <input type='hidden' name='Error3' value='2'><br>
        </form>
    
        <script>
        document.getElementById('myForm').submit();
        </script>
    
        </body>
        </html>
        ";
    }else{
	header("location: superUser.php?id=$id");
    }
}

?>
