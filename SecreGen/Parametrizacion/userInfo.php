<?php 
    $codigo = $_SESSION['id'];
    $query = $db->prepare('SELECT nombre, numero_documento, usuario FROM usuarios WHERE codigo_Us = :u');
    $query->bindParam(':u', $codigo, PDO::PARAM_STR);
    $query->execute();
    $userInfo = $query->fetch(PDO::FETCH_OBJ);
?>



<!-- Inicio Sesion Modal -->
<div class="modal fade" id="userInfo" tabindex="-1" aria-labelledby="exampleModalLabel" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="bg-ligh" id="loginForm" method="POST" action="./passwordUpdate.php" >
        <div class="modal-content">
            <div class="modal-header" style="background-color: #037207">
                <h1 class="modal-title fs-5 text-white" id="exampleModalLabel">Informacion Usuario</h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-floating mb-3">
                    <input type="text" id="userName" name="" class="form-control " value="<?php echo $userInfo->nombre ?>" disabled>
                    <label for="userName">Nombre Usuario</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="number" id="idUser" name="" class="form-control input_user" value="<?php echo $userInfo->numero_documento ?>"  disabled>
                    <label for="idUser">Documento</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="email" id="emailUser" name="" class="form-control" value="<?php echo $userInfo->usuario ?>" disabled>
                    <label for="emailUser">Correo electronico</label>
                </div>
                <div class="has-validation">
                    <div class="form-floating mb-3 " id="feedbackAlert">
                        <input type="password" class="form-control" id="passwordUser" name="password" placeholder="Actualizar Contraseña">
                        <label for="passwordUser">Actualizar Contraseña</label>
                    </div>
                    <div class="form-floating">
                        <input type="password" class="form-control" id="confirmPasswordUser" placeholder="Confirmar Contraseña">
                        <label for="confirmPasswordUser">Confirmar Contraseña</label>
                    </div>
                    <div class="invalid-feedback" id="feedbackResponse">
                        Please choose a username.
                    </div>
                </div>

                
                    <input type="hidden" name="id" value="<?php echo $codigo ?>">
            </div>
            <div class="modal-footer">
                <button type="submit" id="userUpdate" class="btn btn-success" style="background-color: #037207" >Actualizar</button>
            </div>
        </div>

        </form>
    </div>
</div>
<script type="module" src="../../Models/passUpdateValidation.js"></script>
