const perfil = {
	follows:function(type, page) {
		loading.start();
		$.post(`${ZCodeApp.url}/perfil-${type}.php?hide=true&page=${page}`, { pid: $('#info').attr('pid') }, req => {
			$(`#perfil_${type}`).html(req.substring(3));
			loading.end(); 
		});
	}
}
/** ACTIVIDAD **/
const actividad = {
	total: 25,
	show: 25,
	cargar(id, ac_do, ac_type) {
		// ELIMINAR
		$('#last-activity-view-more').remove();
		if(ac_do == 'filtrar') actividad.total = 0;
		// ENVIAMOS
		const sendObj = { pid: $('#info').attr('pid'), ac_do, do: ac_do, start: actividad.total };
		$.post(`${ZCodeApp.url}/perfil-actividad.php`, sendObj, res => {
			const message = res.substring(3);
			const action = parseInt(res.charAt(0));
			if(action === 0) UPModal.alert('Error', message);
			if(action === 1) {
				let typeAttr = (ac_do === 'more') ? 'append' : 'html';
				$('#last-activity-container')[typeAttr](message);
				// TOTALES
				let total_pubs = $('#total_acts').attr('val');
				actividad.total = actividad.total + parseInt(total_pubs);
				$('#total_acts').remove();
			}
		});
	},
	borrar(acid, obj) {
		$.post(`${ZCodeApp.url}/perfil-actividad.php`, { pid: $('#info').attr('pid'), acid, do: 'borrar' }, res => {
			if(res.charAt(0) === '0') UPModal.alert('Error', res.substring(3));
			else $(obj).parent().parent().parent().remove();
		});
	}
}
/** MURO **/
const muro = {
	maxWidth: 463,
	caracteres: 420, // 420 caracteres
	placeholder: {
		foto: ZCodeApp.url + '/files/images/ejemplo.png',
		enlace: ZCodeApp.url + '/blog/A32s1/ejemplo.html',
		video: 'https://www.youtube.com/watch?v=f_30BAGNqqA'
	},
	inpfile: '',
	extensiones: ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'jfif'],
	stream: {
		total: 0, // TOTAL DE PUBLICACIONES CARGADAS
		show: 10, // CUANTOS SE MUESTRAN POR CADA CARGA
		type: 'status', // TIPO D PUBLICACION ACTUAL
		status: 0, // PARA EVITAR CLICKS INESESARIOS
		adjunto: '', // SE HA CARGADO UN ARCHIVO ADJUNTO?
		// CARGAR EL TIPO DE PUBLICACION :
		load(aid, obj) {
			// ACTUAL
			muro.stream.type = aid;
			//
			if (aid != 'stream' && aid != 'foto') {
				let placeholder = muro.placeholder[aid];
				const form = `<div class="frame-input-group my-3 d-flex justify-content-start align-items-center">
					<input class="frame-input rounded px-2 flex-grow-1 h-100" type="text" name="i${muro.stream.type}" placeholder="${placeholder}">
					<div class="frame-input-button rounded px-3 h-100" role="button" onclick="muro.stream.adjuntar()">Adjuntar</div>
				</div>`;
				$(".input-append").html(form);
			} else if (aid == 'foto') {
				$(".input-append").html(`<div class="frame-file-group my-3">
					<input class="frame-input" type="file" name="i${muro.stream.type}">
					<div id="progress"></div><div id="preview"></div>
				</div>`);
				$('input[name="ifoto"]').on('change', function() {
					muro.stream.type = 'foto';
					imported('perfil/image-upload.js', 'handleImageUploadFn', this.files[0]);
				});
			} else {
				$(".input-append").html('')
				$('.shout__buttons').hide();
				muro.stream.type = 'status';
			}
			//
			$('.shout__pub > div').removeClass('active');
			$(obj).addClass('active')
			// 
			$('#attaContent > div').hide();
			$('#' + aid + 'Frame').show();
			return false;
		},
		// ADJUNTAR ARCHIVO EXTERNO : FOTO, ENLACE, VIDEO DE YOUTBE
		adjuntar() {
			// SI ESTA OCUPADO NO HACEMOS NADA
			if (muro.stream.status == 1) return false;
			else muro.stream.status = 1;
			// LOADER
			muro.stream.loader(true, 'Adjuntando...');
			// FUNCION
			let inpt = $(`input[name=i${muro.stream.type}]`);
			let valid = muro.stream.validar(inpt);
			if (valid == true) {
				// ADJUNTAMOS...
				inpt.attr('disabled', 'true');
				muro.stream.ajaxCheck(inpt.val(), inpt);
			} else {
				UPModal.alert('Error al publicar', valid);
				// LOADER / DISABLED / STATUS
				muro.stream.loader(false);
				inpt.removeAttr('disabled').val('');
				muro.stream.status = 0;
			}
		},
		// VERIFICAR ARCHIVO
		ajaxCheck(url, inpt) {
			$.post(`${ZCodeApp.url}/muro-stream.php?do=check&type=${muro.stream.type}`, { url }, res => {
				const message = res.substring(3);
				switch (res.charAt(0)) {
					case '0': //Error
						UPModal.alert('Error al publicar', message);
						inpt.attr('disabled', '');
					break;
					case '1': //OK
						muro.stream.adjunto = inpt.val();
						$('.input-append').html(message);
						$('.shout__buttons').hide();
					break;
				}
			}).done(() => {
				muro.stream.loader(false);
				muro.stream.status = 0;
			});
		},
		// VALIDAR LAS URL DE LOS ARCHIVOS ADJUNTOS
		validar(inpt) {
			let val = inpt.val();
			const regex = /^(ht|f)tps?:\/\/\w+([\.\-\w]+)?\.([a-z]{2,3}|info|mobi|aero|asia|name)(:\d{2,5})?(\/)?((\/).+)?$/i;
			//
			if (empty(val) || regex.test(val) == false) {
				return 'Debes ingresar una direcci&oacute;n URL v&aacute;lida.';
			} else {
				switch (muro.stream.type) {
					case 'foto':
						inpt.val(val.replace(' ', ''));
						var ext_img = inpt.val().slice((inpt.val().lastIndexOf(".") - 1 >>> 0) + 2);
						if (muro.extensiones.indexOf(ext_img) === -1) {
							return 'S&oacute;lo se permiten im&aacute;genes .jpg, .jpeg, .png, .gif, .webp, .svg y .jfif';
						}
					break;
					case 'video':
						if(isYoutube(val) === false) return 'Al parecer la url del video no es v&aacute;lida. Recuerda que solo puedes compartir videos de YouTube.';
					break;
				}
				return true;
			}
		},
		// COMPARTIR
		compartir() {
			// SI ESTA OCUPADO NO HACEMOS NADA
			if(muro.stream.status == 1) return false;
			else muro.stream.status = 1;
			// LOADER
			muro.stream.loader(true);
			// 
			const error_length = `Las publicaciones de estado y/o comentarios deben ser inferiores a ${muro.caracteres} caracteres. Ya has ingresado `;
			// ARCHIVOS ADJUNTOS
			if(muro.stream.type != 'status') {
				if(muro.stream.adjunto != '') {
					let val = $('#wall').val();
					// VALIDAR
					if(val.length > muro.caracteres) {
						UPModal.alert('Error al publicar', `${error_length} ${val.length} caracteres.`);
						// LOADER/ STATUS
						muro.stream.loader(false);
						muro.stream.status = 0;
					// ENVIAMOS PUBLICACION
					} else {
						val = (!val.trim().length) ? '' : val;
						muro.stream.ajaxPost(val);
					}
				} else {
					  UPModal.alert('Error al publicar', 'Ingresa la <strong>URL</strong> en el campo de texto y a continuaci&oacute;n da clic en <strong>Adjuntar</strong>.');
					  // LOADER/ STATUS
					  muro.stream.loader(false);
					  muro.stream.status = 0;
				 }
			// PUBLICACION SIMPLE
			} else if(muro.stream.type == 'status') {
				let status = $('#wall');
				let val = status.val();
				let error = false;
				// VALIDAR
				if (empty(val)) {
					status.blur();
					error = true;
					// LOADER/ STATUS
					muro.stream.loader(false);
					muro.stream.status = 0;
					return false;
				} else if(val.length > muro.caracteres) {
					error = `${error_length} ${val.length} caracteres.`;
				}
				// ENVIAR PUBLICACION
				if(error == false) {
					muro.stream.ajaxPost(val);
				} else {
					UPModal.alert('Error al publicar', error);
					// LOADER/ STATUS
					muro.stream.loader(false);
					muro.stream.status = 0;
				}
			}
		},
		// POSTEAR EN EL MURO
		ajaxPost(data) {
			loading.start();
			const params = [
				'adj=' + muro.stream.adjunto,
				'data=' + encodeURIComponent(data),
				'pid=' + $('#info').attr('pid')
			].join('&');
			$.post(`${ZCodeApp.url}/muro-stream.php?do=post&type=${muro.stream.type}`, params, req => {
				console.log(req)
				switch(req.charAt(0)){
					case '0': //Error
						UPModal.alert('Error al publicar', req.substring(3));
					break;
					case '1': //OK
						if ($('#wall-content .empty')) $('#wall-content .empty').hide();
						$('#wall-content, #news-content').prepend($(req.substring(3)).fadeIn('slow'));
						let plax = $('#wall').attr('placeholder');
						$('#wall').val('').attr({ placeholder: plax }).focus();
						muro.stream.load('status', $('#stMain'));
					break;
				}
				loading.end();
			}).done(() => {
				// LOADER/ STATUS
				muro.stream.loader(false);
				muro.stream.status = 0;
				loading.end(); 
			});
		},
		loadMore(type) {
			// SI ESTA OCUPADO NO HACEMOS NADA
			if(muro.stream.status == 1) return false;
			else muro.stream.status = 1;
			// LOADER
			$('.more-pubs span[role="button"]').hide();
			$('.more-pubs .svg').show();
			// CARGAMOS
			loading.start();
			$.post(`${ZCodeApp.url}/muro-stream.php?do=more&type=${type}`, { pid: $('#info').attr('pid'), start: muro.stream.total }, req => {
				switch(req.charAt(0)){
					case '0': //Error
						UPModal.alert('Error al cargar', req.substring(3));
					break;
					case '1': //OK
						// CARGAMOS AL DOM
						$('#' + type + '-content').append(req.substring(3));
						// VALIDAMOS
						let total_pubs = $('#total_pubs').attr('val');
						total_pubs = parseInt(total_pubs);
						// 
						let msg = (type == 'news' && total_pubs < 0) ? 'Solo puedes ver las &uacute;ltimas 100 publicaciones.' : 'No hay m&aacute;s mensajes para mostrar.'; 
						if(total_pubs == 0 || total_pubs < muro.stream.show) $('.more-pubs').html(msg).css('padding','10px');
						else muro.stream.total = muro.stream.total + parseInt(total_pubs);
						// REMOVER
						$('#total_pubs').remove();
					break;
				}
				loading.end() 
			}).done(() => {
				$('.more-pubs span[role="button"]').show();
				$('.more-pubs .svg').hide();
				muro.stream.status = 0;
				loading.end();
			});
		},
		// LOADER
		loader(active, text = 'Publicando') {
			if (active == true) $('#muroStrem').append(`<div class="shout-status fw-semibold position-absolute z-3">${text}...</div>`);
			else if (active == false) $('#muroStrem .shout-status').remove();
		}
	},
	// LIKE
	like_this(id, type, obj) {
		muro.stream.status = 1;
		// MANDAMOS
		loading.start();
		$.post(`${ZCodeApp.url}/muro-likes.php`, `id=${id}&type=${type}`, req => {
			let reqText = req.text;
			let emptyText = !empty(reqText);
			if(req.status === 'ok') {
				$(obj).text(req.link);
				// Publicacion
				if(type === 'pub') {
					// #lk_ + id
					$(`#like_text--${id}`).html(reqText).parent().parent()[(emptyText ? 'show' : 'hide')]();
					// #cb_ + id
					if(emptyText) $(`#comment_pub--${id}`).show();
				// #lk_cm_ + id
				} else $(`#like_comment--${id}`).text(reqText).parent()[(emptyText ? 'show' : 'hide')]();
			} else {
				UPModal.alert('Error:', reqText);
			}
			loading.end();
		}, 'json')
		.done(() => muro.stream.status = 0);
	},
	show_likes: function(id, type){
		muro.stream.status = 1;
		// MANDAMOS
		loading.start();
		$.post(`${ZCodeApp.url}/muro-likes.php?do=show`, { id, type }, req => {
			let sStatus = parseInt(req.status);
			if(sStatus === 0) UPModal.alert('Error', req['data']);
			if(sStatus === 1) {
				let sHtml = '<ul id="show_likes">';
				for(let iterar = 0; iterar < req.data.length; iterar++) {
					const { user_name, user_id } = req.data[iterar];
					let src = `${ZCodeApp.images.assets}/avatar/${user_id}.webp`;
					sHtml += `<li>
						<a href="${ZCodeApp.url}/perfil/${user_name}"><img src="${src}" class="avatar avatar-5"></a>
						<div class="name fw-semibold">
							<a href="${ZCodeApp.url}/perfil/${user_name}">${user_name}</a>
						</div>
					</li>`
				}
				sHtml += '</ul>';
				// Mostramos
				UPModal.setModal({
					title: 'Personas a las que les gusta',
					body: html,
					buttons: {
						confirmShow: false,
						cancelShow: true,
						cancelTxt: 'Cerrar'
					}
				});
			}
		}, 'json')
		.done(() => muro.stream.status = 0);
	},
	show_comment_box(id) {
		// #cb_ + id
		$(`#comment_pub--${id}`).slideDown()  
	},
	comentar(id) {
		// #cf_ + id
		const idComment = `#comment--${id}`;
		let commentText = $(idComment).val();
		muro.stream.status = 1;
		if(commentText == '' || commentText == $(idComment).attr('title')) {
			$(idComment).focus(); 
			// LOADER/ STATUS
			muro.stream.loader(false);
			muro.stream.status = 0; 
			return false;
		}
		loading.start();
		const param = ['data=' + encodeURIComponent(commentText), 'pid=' + id].join('&');
		$.post(`${ZCodeApp.url}/muro-stream.php?do=repost`, param, req => {
			let cmStatus = parseInt(req.charAt(0));
			let cmMessage = req.substring(3);
			if(cmStatus === 0) UPModal.alert('Error:', cmMessage);
			if(cmStatus === 1) {
				console.log(cmMessage);
				$(`#list_comments--${id}`).append(cmMessage).fadeIn('slow');
				$(idComment).val('');
			}
			loading.end();
		})
		.done(() => {
			muro.stream.status = 0;
			loading.end();
		});
	},
	more_comments: function(id, obj){
		// LOADER / STATUS
		muro.stream.status = 1;
		const finder = $(obj).parent();
		finder.find('span[role="button"]').hide();
		finder.find('img').show();
		//
		loading.start();
		$.post(`${ZCodeApp.url}/muro-stream.php?do=more_comments`, `pid=${id}`, req => {
			let mcStatus = parseInt(req.charAt(0));
			let mcMessage = req.substring(3);
			if(mcStatus === 0) UPModal.alert('Error:', mcMessage);
			if(mcStatus === 1) $(`#list_comments--${id}`).html(mcMessage);
			loading.end();
			finder.find('span[role="button"]').show();
			finder.find('img').hide();
		})
		.done(() => {
			muro.stream.status = 0;
			loading.end();
		});
	},
	// MOSTRAR VIDEO DEL MURO
	load_atta: function(type, ID, obj){
		switch(type) {
			case 'foto':
				$(obj).addClass('image-open').html(`<img src="${ID}" class="w-100 h-100 object-fit-cover pe-none" />`);
			break;
			case 'video':
				$(obj).addClass('only-video');
				$('.muro-video--description').remove();
			break;
		}
	},
	// ELIMINAR PUBLICACION / COMENTARIO
	del_pub: function(id, type){
		let txt_type = (type == 1) ? 'publicaci&oacute;n' : 'comentario';
		let txt_aux = (type == 1) ? 'a ' : 'e ';
		//
		UPModal.setModal({
			title: `Eliminar ${txt_type}`,
			body: `¿Seguro que quieres eliminar est${txt_aux}${txt_type}?`,
			buttons: {
				confirmTxt: `Eliminar ${txt_type}`,
				confirmAction: `muro.eliminar(${id}, ${type})`,
				cancelShow: true
			}
		});
	},
	// ELIMINAR PUBLICACION / COMENTARIO
	eliminar: function(id, type){
		// LOADER / STATUS
		muro.stream.status = 1;
		let snd_type = (type == 1) ? 'pub' : 'cmt';
		//
		loading.start();
		$.post(`${ZCodeApp.url}/muro-stream.php?do=delete`, `id=${id}&type=${snd_type}`, req => {
			let eStatus = parseInt(req.charAt(0));
			let eMessage = parseInt(req.substring(3));
			if(eStatus === 0) UPModal.alert('Error:', eMessage);
			if(eStatus === 1) {
				UPModal.close();
				$(`#${snd_type}_${id}`).hide().remove();
			}
			loading.end();
		})
		.done(() => {
			muro.stream.status = 0;
			loading.end(); 
		});
	}
}
function loadFilter(type) {
	imported('perfil/tabs.js', 'handleLoadFilter', type);
}
/** READY **/
$(() => {
	// ENVIAR PUBLICACION
	$('textarea[name="add_wall_comment"]').on("keypress", function(tecla) {
		if(tecla.code === 'Enter' || tecla.charCode === 13 || tecla.key === 'Enter'){
			let pub_id = parseInt($(this).parent().parent().find('input[name="pid"]').val());
			muro.comentar(pub_id);
			return false;
		}
	});

	const perfilTabs = $('.userPerfil--item');
	perfilTabs.map( function(element, index) {
		$(this).on('click', function() {
			const obj = $(this);
			const classObj = '.userPerfil--item';
			imported('perfil/tabs.js', 'loadTabs', { obj, classObj });
		});
	});

	let div = $('#wall');
	if(div.length > 0) {
		imported('perfil/content-editable.js', 'handleContentEditable', div);
	}
	$(window).on('paste', function(response) {
		let items = response.originalEvent.clipboardData.items;
		for (let i = 0; i < items.length; i++) {
			if (items[i].type.indexOf('image') !== -1) {
				let file = items[i].getAsFile();
				muro.stream.type = 'foto';
				imported('perfil/image-upload.js', 'handleImageUploadFn', file);
				break;
			}
		}		
	});

	$('#attach-file').on('click', () => $('.shout__buttons').toggle());
});