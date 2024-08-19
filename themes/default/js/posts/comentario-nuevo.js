export function handleCommentAndReply({ type = 'posts', cid = 0, mostrar_resp = true }) {
	cid = parseInt(cid);
	let object = { mostrar_resp }
	if(cid !== 0) object = {...object, cid }
	const btnComment = getButton(cid);
	const textarea = getTextarea(cid);
	let text = textarea.bbcode();
	let auser = parseInt($('#auser_post').val());
	let comentario = encodeURIComponent(text);
	let totalComments = parseInt($('#ncomments').text());
	btnComment.attr({ disabled: 'disabled' });
	verify(btnComment, text, textarea);
	// Mostramos el mensaje de enviar comentario...
	$('#send_comment' + cid).show();
	$('.box-comment' + cid).hide();
	loading.start();
	//
	let params = { type, comentario, mostrar_resp, auser };
	if(type === 'posts') {
		params = {...params, postid: ZCodeApp.postid }
	} else {
		params = {...params, fotoid: ZCodeApp.fotoid }
	}
	if(cid > 0) params = {...params, respuesta: parseInt($('#respuesta_' + cid).val()) };
	$.post(`${ZCodeApp.url}/comentario-agregar.php`, params, response => {
		let message = response.substring(3);
		if(response.charAt(0) === '0') {
			$('#send_comment' + cid).hide();
			$('.box-comment--message' + cid + ' > .error').addClass('empty').html(message).show();
			btnComment.removeAttr('disabled');
		} else {
			$('#preview').remove();
			$((cid > 0 ? '#respuestas'+cid : '#nuevos')).html(response).slideDown('slow', function () {
				$('#no-comments').hide('slow');
				if(cid > 0) $('.miComentario').html('<div class="empty">Tu comentario fue agregado correctamente!</div>');
			});
			$('#ncomments').text(totalComments + 1);
			$('.wysibb-body').html('');
		}
		loading.end()
		$('.send_comment').hide();
		UPModal.close();
	});
}

function getButton(cid) {
	const buttonComment = '.button-comment';
	const buttonCommentCid = `${buttonComment}[data-button="${cid}"]`;
	return $((cid > 0 ? buttonCommentCid : buttonComment));
}

function getTextarea(cid) {
	const bxComentar = '#boxComentar';
	const bxResponder = `.boxResponder[data-reply="${cid}"]`;
	return $((cid > 0 ? bxResponder : bxComentar));
}

function verify(btnComment, text, textarea) {
	const lengthMax = 500;
	if(empty(text) || text.length >= lengthMax) {
		textarea.focus();
		btnComment.removeAttr('disabled');
		if(text.length >= lengthMax) {
			UPModal.alert('Ups!', `Tu comentario no puede ser mayor a ${lengthMax} caracteres.`, false);
		} 
		return false;
	}
}