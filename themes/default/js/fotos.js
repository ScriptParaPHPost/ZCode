function countUpperCase(string) {
	var len = string.length, 
	strip = string.replace(/([A-Z])+/g, '').length, 
	strip2 = string.replace(/([a-zA-Z])+/g, '').length, 
	percent = (len  - strip) / (len - strip2) * 100;
	return percent;
}
// Función para mostrar u ocultar errores
function manejarError(elemento, mensaje, esError) {
   let metodoClase = esError ? 'addClass' : 'removeClass';
   let metodoVisibilidad = esError ? 'show' : 'hide';
   elemento.closest('.upform-status')[metodoClase]('error').html(mensaje)[metodoVisibilidad]();
}

const fotos = {
   regexUrl: /^(ht|f)tps?:\/\/\w+([\.\-\w]+)?\.([a-z]{2,3}|info|mobi|aero|asia|name)(:\d{2,5})?(\/)?((\/).+)?$/i,
	extensiones: ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'jfif'],
	validaUrl(obj, url) {
		let extension = url.split('.').pop().toLowerCase();
		// URL VALIDA
		if (!this.regexUrl.test(url)) {
         manejarError(elemento, 'No es una dirección válida.', true);
         return false;
      }
      if (!this.extensiones.includes(extension)) {
         manejarError(elemento, 'Solo se permiten imágenes con extensiones válidas.', true);
         return false;
      }
      return true;
	},
	agregar(){
		let hayError = false;
		$('.required').each(function () {
         let campo = $(this);
         let valor = $.trim(campo.val());
         if (!valor) {
            manejarError(campo, 'Este campo es obligatorio.', true);
            hayError = true;
            return false;
         }

         if (campo.attr('name') === 'url' && !fotos.validaUrl(campo, valor)) {
            hayError = true;
            return false;
         }
      });
		//
		if (hayError) return false;
		let descripcion = $('textarea[name="description"]');
      if (descripcion.val().length > 1500) {
         manejarError(descripcion, 'La descripción no debe exceder los 1500 caracteres.', true);
         return false;
      }
		// ENVIAMOS
		$('.fade_out').fadeOut("slow", () => $('.loader').fadeIn());
		$('form[name=add_foto]').submit();
	},
	comentar(type) {
		let obj = { type, mostrar_resp: true }
		imported('posts/comentario-nuevo.js', 'handleCommentAndReply', obj);
	},
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
	// Chequeamos el titulo
	$titulo.on('keyup', () => {
		const titleVal = $titulo.val();
		let checkTitle = (titleVal.length >= 5 && countUpperCase(titleVal) > 59) 
		manejarError($titulo, 'El t&iacute;tulo no debe estar en may&uacute;sculas', checkTitle);
		return false;
	});
	//Editor de posts comentarios
	if( $('#boxComentar').length ) {
		$('#boxComentar').css({ height: 80 }).html('').wysibb({ buttons: "smilebox,|,bold,italic,underline,strike,img,link" });
	}
});