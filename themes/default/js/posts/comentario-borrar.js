export function handleDeleteComment({ comid, autor, postid, status }) {
	postid = postid || gget('postid');
	if( !status ) {
		UPModal.setModal({
			title: 'Borrar Comentario',
			body: '&#191;Quiere eliminar este comentario?',
			buttons: {
				confirmTxt: 'Borrar comentario',
				confirmAction: `comentario.borrar(${comid}, ${autor}, ${postid}, true)`,
				cancelShow: true
			}
		});
	} else {
		loading.start();
		$.post(`${ZCodeApp.url}/comentario-borrar.php`, { comid, autor, postid }, response => {
			if(parseInt(response.charAt(0)) === 0) UPModal.alert('Error', response.substring(3));
			else {
				UPModal.close();
				UPModal.alert('Listo', response.substring(3), false);
				// RESTAMOS
				$('#ncomments').text(totalComments() - 1);
				$('#comment' + comid).remove();
				loading.end();
			}
		}).fail(() => {
			UPModal.error_500("comentario.borrar('"+comid+"')");
			loading.end();
		});
	}
}