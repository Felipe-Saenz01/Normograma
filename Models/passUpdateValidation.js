const pass1 = document.getElementById('passwordUser');
const pass2 = document.getElementById('confirmPasswordUser');
const groupValidation = document.getElementById('feedbackAlert');
const validationResponse = document.getElementById('feedbackResponse');
const btnUpdate = document.getElementById('userUpdate');

function valid(){
    pass1.classList.remove('is-invalid');
    pass2.classList.remove('is-invalid');
    pass1.classList.add('is-valid');
    pass2.classList.add('is-valid');
    groupValidation.classList.remove('is-invalid');
    groupValidation.classList.remove('is-valid');
    validationResponse.classList.remove('invalid-feedback');
    validationResponse.classList.remove('valid-feedback');
    validationResponse.textContent = '';    
    btnUpdate.disabled = false;
}
function inValid(texto){
    pass1.classList.add('is-invalid');
    pass2.classList.add('is-invalid');
    groupValidation.classList.add('is-invalid');
    validationResponse.textContent = texto;
    btnUpdate.disabled = true;
}
const filtroPassword = /^(?=.*[0-9].*[0-9].*[0-9].*[0-9])(?=.*[a-zA-Z])([a-zA-Z0-9]+){8,}$/;

pass1.addEventListener('input', e =>{
    e.preventDefault();
    const nuevoValor = e.target.value

    if(pass2.value.trim() === ''){

    }else{
        if(pass2.value == nuevoValor){
            if(filtroPassword.test(pass1.value)){
                valid();
            }else{
                inValid('La contraseña debe tener minimo 8 caracteres y 4 números.');
            }
        }else{
            inValid('Las contraseñas no coinciden')
        }
    }
    
    
})


pass2.addEventListener('input', e =>{
    e.preventDefault();

    const nuevoValor = e.target.value
    if(pass1.value == nuevoValor){
        if(filtroPassword.test(pass1.value)){
            valid();
        }else{
            inValid('La contraseña debe tener minimo 8 caracteres y 4 números.');
        }
    }else{
        inValid('Las contraseñas no coinciden')
    }
})
