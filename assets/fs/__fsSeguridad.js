export function TFactorAuthSecurity() {
   if($('small#countdown').length > 0) {
	   startCountdown();
		$.post(`${ZCodeApp.url}/cuenta-qr-regenerate.php`, function(response) {
	      $('#regenerate').html(response); // Mostramos
	   });
	}
};

function startCountdown() {
   let countdown = 30; // Duración del contador en segundos
   let interval = setInterval(function() {
      countdown--; // Decrementa el contador
      $("#countdown").text(`${countdown}s`); // Actualiza el texto del contador

      if (countdown <= 0) {
         clearInterval(interval); // Detiene el contador cuando llega a 0
         $('#regenerate').html(''); // Limpiamos
         // Realiza una llamada AJAX usando $.post
         $.post(`${ZCodeApp.url}/cuenta-qr-regenerate.php`, response => $('#regenerate').html(response));
         // Reinicia el contador
         startCountdown();
      }
   }, 1000); // Intervalo de 1 segundo (1000 ms)
}