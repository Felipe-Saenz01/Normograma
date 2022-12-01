<?php
    $file = $_GET['ruta'];
    $id= $_GET['id'];
if(is_file($file)){
    chmod($file, 07777);
    if(!unlink($file)){
	echo false;
    }else{
	header("location: SecreGen.php?id=$id");
    }
}

?>
