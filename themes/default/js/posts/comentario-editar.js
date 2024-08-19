export function handleCommentEdit({ cid, gew }) {
	$(`#comment${cid} .dropdown-options[dropdown="${cid}"]`).hide();
	if(!gew) {
		const message = $(`#comment${cid} .comment--body`).data('reply');
		UPModal.setModal({
			size: 'lg',
			title: 'Editar Comentario',
			body: `<textarea id="edit-comment-${cid}" title="Escribir un comentario...">${message}</textarea>`,
			buttons: {
				confirmTxt: 'Editar comentario',
				confirmAction: `comentario.editar(${cid}, true)`,
				cancelShow: true
			}
		});
		$('#edit-comment-' + cid).css({ height: 80 }).wysibb({ buttons: "smilebox,|,bold,italic,underline,strike,img,link" })
	} else {
		loading.start(); 
		const comentario = $('#edit-comment-' + cid).bbcode();
		$.post(`${ZCodeApp.url}/comentario-editar.php`, { comentario, cid }, req => {
			if(req.charAt(0) === '0') {
				UPModal.alert('Error', req.substring(3));
			} else {
				$(`#comment${cid} .comment--body`).html(req.substring(3));
			}
			loading.end();
			UPModal.close();
		});
	}
}