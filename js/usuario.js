var senha = document.getElementById("senha");
var confirmeSenha = document.getElementById("confirme_senha");

function validaSenha(){
  if(senha.value != confirmeSenha.value) {
    confirmeSenha.setCustomValidity("As senhas não conferem.");
  } else {
    confirmeSenha.setCustomValidity('');
  }
  if(senha.value.match(/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])([a-zA-Z0-9]{8})$/) == null){
      senha.setCustomValidity("A senha deve conter caracter minúsculo, maiúsculo, números e 8 dígitos.");
  }else{
      senha.setCustomValidity('');
  }
}

senha.onchange = validaSenha;
confirmeSenha.onkeyup = validaSenha;