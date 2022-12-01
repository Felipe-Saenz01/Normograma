<?php

class Database{

    private $host;
    private $db;
    private $user;
    private $password;
    private $charset;

    public function __construct(){
        $this->host = 'localhost';
        $this->db = 'normograma';
        $this->user = 'roott';
        $this->password = '';
        $this->charset = 'utf8mb4';
    }

    function connect(){
        try{
            $connection = "mysql:host=" . $this->host . ";dbname=" . $this->db . ";charset=" . $this->charset;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            
            $pdo = new PDO($connection, $this->user, $this->password, $options);
    
            return $pdo;
        }catch(PDOException $e){
            //print_r('Error connection: ' . $e->getMessage());
            echo " <script>
            Swal.fire({
                title: 'Fallo de conexión!',
                text: 'Existen problemas con la conexion, comuniquese con un administrador.',
                icon: 'info',
                allowOutsideClick: false,
                allowEscapeKey:false,
                allowEnterKey:false,
                confirmButtonColor: '#037207',
                confirmButtonText: 'Aceptar'
            })
            </script>";
        }
    }

}

?>
