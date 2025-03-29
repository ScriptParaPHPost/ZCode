export function handleReactionComment({ cid, reaccion }) {
	const { postid } = ZCodeApp;
	$.post(`${ZCodeApp.url}/comentario-reaccion.php`, { cid, postid, reaccion }, req => {
		UPModal.alert((parseInt(req.charAt(0) === 0) ? 'Error' : 'Bien'), req.substring(3), false);
	})
}