var avatar = {
	size: 160,
	uid: false,
	key: false,
   ext: false,
	informacion: '',
	current: false,
	success: false,
	total: 2,
	fetching: async (page, data) => {
		const uploader = await fetch(`${ZCodeApp.url}/upload-${page}.php`, {
			method: 'POST',
			body: data
		});
		const response = await uploader.json();
		return response;
	},
	subir: async (type = 'desktop') => {
		$(".avatar-loading").show();
		const myInput = $(`input.browse[name=${type}]`);
		const datoUrl = new FormData();
		datoUrl.append('url', (type === 'url') ? myInput.val() : myInput[0].files[0]);
		const Response = await avatar.fetching('avatar', datoUrl);
		
		if(!empty(Response)) avatar.subida_exitosa(Response);
	},
	subida_exitosa: rsp => {
		$(".verify").removeClass('load');
		if (rsp.error == 'success') avatar.success = true;
		else if (rsp.msg) {
         avatar.key = rsp.key;
         avatar.ext = rsp.ext;
         avatar.cortar(rsp.msg);
		} else cuenta.alerta(rsp.error, 0);
		$(".avatar-loading").hide();
	},
	cortar: img => {
		newImageUpload = img + '?t=' + string_random(10);
		UPModal.setModal({
			title: 'Cortar avatar',
			body: `<img class="avatar-cortar" src="${newImageUpload}" />`,
			buttons: {
				confirmAction: `avatar.guardar()`,
				confirmTxt: 'Cortar imagen',
				cancelShow: true
			}
		});
		$("#avatar-img, #avatar-menu").attr("src", newImageUpload).on('load', () => {
			let sizes = [avatar.size, avatar.size, 'px'];
			var croppr = new Croppr('.avatar-cortar', {
			   aspectRatio: 1, // Mantemos el tamanio cuadrado 1:1
			   // Minimo de 120px x  120px
    			startSize: sizes, 
    			minSize: sizes, 
    			// Enviamos las coordenadas para cortar la imagen
    			// Tiene la funcion onCropEnd ya que es como va a quedar
    			onCropEnd: data => avatar.informacion = data ?? avatar.vistaPrevia,
            onCropMove: avatar.vistaPrevia
			});
		});
	},
	vistaPrevia: function (coords) {
		let rx = avatar.size / coords.width;
		let ry = avatar.size / coords.height;
		$('#avatar-img').css({
			width: Math.round(rx * coords.width) + 'px',
			height: Math.round(ry * coords.height) + 'px',
			marginLeft: '-' + Math.ceil(rx * coords.x) + 'px',
			marginTop: '-' + Math.round(ry * coords.y) + 'px'
		});
	},
	recargar: () => $("#avatar-img, #avatar-menu").attr("src", avatar.current + '?t=' + string_random(10)),
	guardar: async () => {
		if (empty(avatar.informacion)) cuenta.alerta('Debes seleccionar una parte de la foto', 0);
		else {
			const allcoord = {
				key: avatar.key,
				ext: avatar.ext,
				x: avatar.informacion.x,
				y: avatar.informacion.y,
				w: avatar.informacion.width,
				h: avatar.informacion.height
			};
			const coordenadas = new FormData();
			for (const prop in allcoord) coordenadas.append(prop, allcoord[prop]);
			const resultado = await avatar.fetching('crop', coordenadas)
			if(resultado.error === "success") {
				UPModal.proccess_end();
			   UPModal.alert('Avatar creador', "Tu avatar se ha creado correctamente...", false);
			   avatar.recargar();
			   $("#input_add").hide();
			   $(`input[name="url"]`).attr({
			   	value: ''
			   })
			}
		}
	}
}