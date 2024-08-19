function countUpperCase(string) {
	var len = string.length, 
	strip = string.replace(/([A-Z])+/g, '').length, 
	strip2 = string.replace(/([a-zA-Z])+/g, '').length, 
	percent = (len  - strip) / (len - strip2) * 100;
	return percent;
}
// Mostramos el error
function error(objeto, mensaje, tipo){
	let addRemove = tipo ? 'addClass' : 'removeClass';
	let showHide = tipo ? 'show' : 'hide';
	objeto.parent().parent().children('.upform-status')[addRemove]('error').html(mensaje)[showHide]();
}

var fotos = {
	regex: /^(ht|f)tps?:\/\/\w+([\.\-\w]+)?\.([a-z]{2,3}|info|mobi|aero|asia|name)(:\d{2,5})?(\/)?((\/).+)?$/i,
	extensiones: ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'jfif'],
	// VOTAR FOTO
	votar(voto, fotoid) {
		// VARS
		const element = $('#votos_total_' + voto);
		let totalVotos = parseInt(element.text());
		totalVotos = totalVotos ?? 0;
		loading.start();
		$.post(`${ZCodeApp.url}/fotos-votar.php`, { voto, fotoid }, req => {
			UPModal.alert('Votar foto', req.substring(3), false);
			if (parseInt(req.charAt(0)) === 1) element.text(++totalVotos);
			loading.end();
		});
	},

	validaUrl: function(obj, url) {
		let ext = url.substr(-3);
		// URL VALIDA
		if(this.regex.test(url) == false){
			error(obj, 'No es una direcci&oacute;n v&aacute;lida', true);
			return false;
		} else if(ext != 'gif' && ext != 'png' && ext != 'jpg'){
			error(obj, 'S&oacute;lo se permiten im&aacute;genes .gif, .png y .jpg', true);
			return false; 
		} else return true;
	},
	agregar: function(){
		let error = false;
		$('.required').each(function(){
			if (!$.trim($(this).val())) {
				error(this, 'Este campo es obligatorio', true);
				error = true;
				return false;
			} else if($(this).attr('name') == 'url'){
				var rimg = fotos.validaUrl(this, $(this).val());
					 if(rimg != true) {
						  error = true;
						  return false;
					 } else error = false;
			}
		  });
		  //
		  if (error) {
			return false;
		} 
		  //
		  if ($('textarea[name=desc]').val().length > 1500) {
			showError($('textarea[name=desc]').get(0), 'La descripci&oacute;n no debe exeder los 1500 caracteres.');
			return false;
		}
		  // ENVIAMOS
		  $('.fade_out').fadeOut("slow",function(){
				$('.loader').fadeIn();  
		  })
		  //
		  $('form[name=add_foto]').submit();
	},
	comentar(type) {
		let obj = { type, mostrar_resp: true }
		imported('posts/comentario-nuevo.js', 'handleCommentAndReply', obj);
	},
	// BORRAR COMENTARIO/ FOTO
	borrar:function(id, type){
		  //
		  var txt_type = (type == 'com') ? 'comentario' : 'foto';
		  var txt_aux = (type == 'com') ? 'este ' : 'esta ';
		  //
		  mydialog.mask_close = false;
		  mydialog.show(true);
		mydialog.title('Eliminar ' + txt_type);
		mydialog.body('¿Seguro que quieres eliminar ' + txt_aux + txt_type);
		mydialog.buttons(true, true, 'Eliminar ' + txt_type, 'fotos.del_' + txt_type + '(' + id + ')', true, true, true, 'Cancelar', 'close', true, false);
		mydialog.center();
	 },
	 // ELIMINAR COMENTARIO
	 del_comentario: function(cid){
		  loading.start() 
		$.ajax({
			type: 'POST',
			url: ZCodeApp.url + '/comentario-borrar.php?do=fotos',
			data: 'cid=' + cid,
			success: function(h){
				switch(h.charAt(0)){
					case '0': //Error
								mydialog.alert('Error:', h.substring(3));
						break;
					case '1': //OK
						var ncomments = parseInt($('#ncomments').text());
						$('#ncomments').text(ncomments - 1);
								//
						$('#div_cmnt_' + cid).slideUp( 1500, 'easeInOutElastic');
						$('#div_cmnt_' + cid).remove();
						//
								mydialog.close();
								//
						break;
				}
					 loading.end() 
			}
		  });
	 },
	 // ELIMINAR FOTO
	 del_foto: function(fid){
		  loading.start() 
		$.ajax({
			type: 'POST',
			url: ZCodeApp.url + '/fotos/borrar.php',
			data: 'fid=' + fid,
			success: function(h){
				switch(h.charAt(0)){
					case '0': //Error
								mydialog.alert('Error:', h.substring(3));
						break;
					case '1': //OK
								mydialog.close();
								location.href = ZCodeApp.url + '/fotos/';
								//
						break;
				}
					 loading.end() 
			}
		  });
	 }

}

$(function() {
	const $titulo = $('input[name=titulo]');
	// Cargamos el editor
	//$('textarea[name="cuerpo"]').css({height: 400}).wysibb();
	// Chequeamos el titulo
	$titulo.on('keyup', () => {
		const titleVal = $titulo.val();
		let checkTitle = (titleVal.length >= 5 && countUpperCase(titleVal) > 59) 
		error($titulo, 'El t&iacute;tulo no debe estar en may&uacute;sculas', checkTitle);
		return false;
	});
	// Contador
	UPEffects.decrypt('up-effect--decrypt');
	//Editor de posts comentarios
	if( $('#boxComentar').length ) {
		$('#boxComentar').css({ height: 80 }).html('').wysibb({ buttons: "smilebox,|,bold,italic,underline,strike,img,link" });
	}
});