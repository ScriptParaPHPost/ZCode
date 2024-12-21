let redirectURI = $('#redirect_uri');
if(redirectURI.length > 0) {
	if(empty(redirectURI.val())) redirectURI.val(`${url}/discord.php`)
	$('#social_name').on('change', () => {
		let replace = $('#social_name option:selected').val() ;
		redirectURI.val(`${url}/${replace}`);
	});
	$("#botonCopiar").on("click", () => {
	   redirectURI.select();
	   document.execCommand("copy");
	   window.getSelection().removeAllRanges();
	   redirectURI.parent().find('small').html("Redirect URL ha sido copiado correctamente!");
	   setTimeout(() => redirectURI.parent().find('small').html(''), 5000);
	});
}