function twoFactorAuth() {
   let code = "", secret = document.getElementById('code_secret').value;
   const numbers = document.querySelectorAll('#one_password_time input');
    
   numbers.forEach(number => code += number.value);
  	const params = new URLSearchParams();
   params.append('code', code);
   params.append('secret', secret);

   fetch(`${ZCodeApp.url}/cuenta-two-factor.php`, {
      method: 'POST',
      headers: {
         'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: params.toString()
   })
  	.then(response => response.json())
   .then(req => {
	   const { status, message } = req;
	   if (status) {
	      let codes = message.split(',');
	      let app = '<div style="display:grid;grid-template-columns:repeat(3, 1fr)">';
	      codes.forEach(code => app += `<span>${code}</span>`);
	      app += '</div>';
	      UPModal.setModal({
	         title: '2FA - Activado',
	         body: 'Guarda estos códigos en un lugar seguro, ya que si el código OPT(2FA) no funciona o lo has perdido, puedes usar estos códigos para acceder.<br><h4>Tokens:</h4>' + app,
	         buttons: {
	            confirmTxt: 'Listo',
	            confirmAction: 'location.reload()',
	         }
	      });
	   } else {
	      UPModal.alert('Lo siento', message, false);
	   }
   });
}

document.querySelector('.verify_2fa').addEventListener('click', twoFactorAuth);

var inputs = document.getElementById("one_password_time");
inputs.addEventListener("input", function (e) {
   const target = e.target;
   const val = target.value;
   if (isNaN(val)) {
      target.value = "";
      return;
   }
   if (val != "") {
      const next = target.nextElementSibling;
      if (next) next.focus();
   }
});
inputs.addEventListener("keyup", function (e) {
   const target = e.target;
   const key = e.key.toLowerCase();
   if (key == "backspace" || key == "delete") {
      target.value = "";
      const prev = target.previousElementSibling;
      if (prev) prev.focus();
      return;
   }
});