<?php
if(!isset($_SESSION['rol'])){
        header('location: ../Login.php');
    }else{
        if($_SESSION['rol'] != 0){
            header('location: ../Login.php');
        }
    }
?>