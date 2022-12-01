<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" href="img/favicon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Spartan:wght@300;600&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/ffec4ec2ed.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css" />
</head>
</head>
<body class="bg-dark">

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/js/bootstrap.min.js"></script>





    <section class="vh-100">
  <div class="container-fluid h-custom align-items-center">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-md-2 col-lg-6 col-xl-7">
        <img src="imgl/Bige3.png"
          class="img-fluid" alt="Sample image">
      </div>
      
      <div class="col-lg-5 bg-dark d-flex flex-column align-items-end min-vh-100">
        <div class="align-self-center w-100 px-lg-5 py-lg-4 p-4">   
            <img src="imgl/logoUni2.png" class="img-fluid" />
        </div>
                
      <div class="align-self-center w-100 px-lg-5 py-lg-4 p-4">
        <form action="Login.php" method="POST">
          
            <h3>Inicia sesión</h3> 
            <br/>
          <!-- Email input -->
          <div class="form-outline mb-4">
            <input type="email" name="t1" class="form-control form-control-lg"
              placeholder="Ingrese su correo" required>
            <label class="form-label " for="form3Example3">Correo electrónico</label>
          </div>

          <!-- Password input -->
          <div class="form-outline mb-3">
            <input type="password" name="t2" class="form-control form-control-lg"
              placeholder="Ingrese su contraseña" required>
            <label class="form-label " for="form3Example4">Contraseña</label>
          </div>



        <div class="text-center text-lg-start mt-4 pt-2">
        <input class="btn btn-sm btn-success" type="submit" value="Ingresar" name="btn_create_normograma" style="background-color:#037207; padding-left: 2.5rem; padding-right: 2.5rem;" >
        </div>
        <?php
        include 'Models/conexion.php';

        session_start();

        if(isset($_SESSION['rol'])){
          switch($_SESSION['rol']){
          case 1:
            header('location: superUser/superUser.php');
          break;
          
          case 2:
            header('location: SecreGen/SecreGen.php');
          break;

          case 3:
            header('location: JustUser/JustUser.php');
          break;
          
          default:
       }
      }
          
      if(isset($_POST['t1']) && isset($_POST['t2'])){
        $username = $_POST['t1'];
        $password = $_POST['t2'];
            
        if(empty($db)){ 
          return;
        }


        $query = $db->prepare('SELECT * FROM usuarios WHERE usuario = :u');
        $query->bindParam(':u', $username, PDO::PARAM_STR);
        $query->execute();
        
        $row = $query->fetch(PDO::FETCH_ASSOC);

          if(is_array($row)){

            $tipo = password_verify($password, $row['clave']);

            if(password_verify($password, $row['clave'])){
                $rol = $row['tipo_de_usuario'];
                $estade = $row['estado'];
                $nombrer = $row['nombre'];
                if($estade != 2){
                  $_SESSION['rol'] = $rol;
                  $_SESSION['usuarioname'] = $nombrer;
                    switch($rol){
                      case 1:
                        header('location: superUser/dashboard.php');
                      break;
          
                      case 2:
                        header('location: SecreGen/dashboard.php');
                      break;

                      case 3:
                        header('location: JustUser/dashboard.php');
                      break;
          
          
                      default:
                    }
                }else{
                  echo "<script>
                  Swal.fire({
                    icon: 'warning',
                    title: 'Cuenta Suspendida!',
                    text: 'Comuniquese con un administrador',
                    confirmButtonColor: '#037207',
                    confirmButtonText: 'Aceptar'
                  })
                  </script>";
                }

                
                
            }else{
              echo "<script>
              Swal.fire({
                icon: 'error',
                title: 'Error al iniciar sesion',
                text: 'Usuario o contraseña incorrectos',
                confirmButtonColor: '#037207',
                confirmButtonText: 'Aceptar'
              })
              </script>";
            }   

          }else{
            echo "<script>
              Swal.fire({
                icon: 'error',
                title: 'Error al iniciar sesion',
                text: 'Usuario o contraseña incorrectos',
                confirmButtonColor: '#037207',
                confirmButtonText: 'Aceptar'
              })
              </script>";
            
        }
                    
                    
      }
        

            ?>
        </form>
      </div>
    </div>
  </div>



</section> 
</body>



</html>
