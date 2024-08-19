export function handleHideComment({ comid, autor }) {
	UPModal.close();
	loading.start();
	$.post(`${ZCodeApp.url}/comentario-ocultar.php`, { comid, autor }, response => {
		let isNumber = parseInt(response.charAt(0));
		if(isNumber === 0) UPModal.alert('Error', response.substring(3));
		$('#comentario_' +comid).css('opacity', (isNumber === 1 ? 1 : 0.5));
		$('#pp_' +comid).css('opacity', (isNumber === 1 ? 0.5 : 1))
		loading.end()
	}).fail(() => {
		UPModal.error_500("comentario.borrar('"+comid+"')");
		loading.end();
	});
}