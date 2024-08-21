const favs = {
	add: () => {
		UPModal.setModal({
			title: 'Añadir favicon',
			body: `<div class="upform-group">
			<label class="upform-label" for="size">Tamaño</label>
				<div class="upform-group-input">
					<input class="upform-input" type="number" name="size" id="size" placeholder="16">
				</div>
			</div>`,
			buttons: {
				confirmTxt: `S&iacute;`,
				confirmAction: `favs.insert()`,
				cancelShow: true
			}
		});
	},
	insert: () => {
		let size = empty($('input#size').val()) ? 16 : $('input#size').val();
		const html = `<div class="input-group w-100 mb-3">
          <span class="input-group-text text-center d-block" style="width: 90px;" id="pixeles">${size}x${size}</span>
      	<input class="form-control" type="text" id="images" name="images[${size}]" value="" />
      	<button type="button" class="btn btnOk" onclick="$(this).parent().remove()">Quitar</button>
      </div>`;
      $('#addFavs').append(html);
      UPModal.close();
	}
}
/**
 * Con estas funciones "sameModal()" y "sameFn()"
 * y de esta forma simplificamos
*/
function sameModal(sametitle, samebody, sameaction) {
   UPModal.setModal({
		title: sametitle,
		body: samebody,
		buttons: {
			confirmTxt: 'S&iacute;',
			confirmAction: sameaction,
			cancelShow: true,
			cancelTxt: 'Cancelar'
		}
	});
}
function sameFn(page, params, element) {
   loading.start();
   UPModal.proccess_start();
	$.post(`${ZCodeApp.url}/${page}.php`, params, a => {
   	UPModal.proccess_end();
   	UPModal.alert((a.charAt(0) == '0' ? 'Opps!' : 'Hecho'), a.substring(3), false);
   	if(a.charAt(0) == '1') $(element).fadeOut().remove(); 
   	loading.end();
   });
}

const database = {
	table_action(action, table, id = 0) {
		loading.start();
		$.post(`${ZCodeApp.url}/database-${action}.php`, { table }, req => {
			let type = (parseInt(req.charAt(0)) === 0) ? 'Error' : 'Bien';
			let msg = req.substring(3);
			if(action === 'optimize') {
				$(`td[data-cache="${id}"]`).html('Vacio');
				$(`span[data-remove="${id}"]`).remove();
			}
			if(id != 0) $(`td[data-update="${id}"]`).html('Hace instantes');
			UPModal.alert(type, msg, false);
			loading.end();
		});
	},
	tablas() {
		let tablas = {};
		$('input[type="checkbox"]').each((i, inpt) => {
			const ic = $(inpt).val();
			if($(inpt).prop('checked') && $(inpt).val() != 'all') tablas[i] = ic;
		});
		return tablas;
	},
	table_all(action) {
		var tablas = this.tablas();
		if($.isEmptyObject(tablas)) {
			UPModal.alert('Espera', 'Debes seleccionar por lo menos una tabla.', false);
		} else {
			$.post(`${ZCodeApp.url}/database-all.php`, { action, tablas }, req => {
				let type = (parseInt(req.charAt(0)) === 1);
				let msg = req.substring(3);
				if(type) {
					Object.values(tablas).forEach((id, n) => {
						let number_id = Object.keys(tablas)[n];
						if(action === 'optimize') {
							$(`td[data-cache="${number_id}"]`).html('Vacio');
							$(`span[data-remove="${number_id}"]`).remove();
						}
						if(number_id != 0) $(`td[data-update="${number_id}"]`).html('Hace instantes');
					});
					UPModal.alert('Bien', msg, false);
				} else {
					UPModal.alert('Error', msg, false);
				}
			});
		}
	},
	create_backup() {
		loading.start();
		var tablas = $('input#todos').prop('checked') ? '*' : this.tablas();
		$.post(`${ZCodeApp.url}/database-backup.php`, { tablas }, req => {
			let type = (parseInt(req.charAt(0)) === 0) ? 'Error' : 'Bien';
			let msg = req.substring(3);
			UPModal.alert(type, msg, false);
			loading.end();
		});
	}
}

const htaccess = {
	backup() {
		$.get(`${ZCodeApp.url}/htaccess-backup.php`, req => UPModal.alert('Bien', 'La copia fue creada correctamente', false))
	}
}

var admin = {
	// AFILIADOS
	afs: {
	   borrar(afid, gew) {
         if(!gew){
         	sameModal('Borrar Afiliado', '&#191;Quiere borrar este afiliado?', `admin.afs.borrar(${afid}, 1)`)
	      } else sameFn('afiliado-borrar', { afid }, `#few_${afid}`);
   	},
   	accion(aid) {
   		loading.start()
   		$.post(ZCodeApp.url +'/afiliado-setactive.php', { aid }, h => {
   			let number = parseInt(h.charAt(0));
				if(number === 0) UPModal.alert('Error', h.substring(3));
				let color = (number === 1) ? 'green' : 'purple';
				let text = (number === 1) ? 'A' : 'Ina';
				$('#status_afiliado_' + aid).html(`<font color="${color}">${text}ctivo</font>`);
		      loading.end()
			});
		}, 
	},
	// NOTICIAS
	news: {
 		accion(nid) {
		   loading.start();
		   $.post(ZCodeApp.url +'/admin-noticias-setInActive.php', { nid }, req => {
   			let number = parseInt(req.charAt(0));
				if(number === 0) UPModal.alert('Error', req.substring(3));
				let color = (number === 1) ? 'success' : 'danger';
				let text = (number === 1) ? 'A' : 'Ina';
				$('#status_noticia_' + nid).html(`<span class="text-${color}">${text}ctiva</span>`);
		      loading.end();
		   })
		},
		borrar(nid, gew) {
	    	if(!gew) {
         	sameModal('Eliminar Noticia', '&#191;Quiere eliminar la noticia?', `admin.news.borrar(${nid}, true)`);
         } else {
         	sameFn('admin-eliminar-noticia', { nid }, `[nid="${nid}"]`);
         }
		}
	},
	// NICKS
	nicks: {
	  	accion(nid, accion, gew) {
	    	if(!gew){
	    		apd = (accion == 'aprobar') ? 'Aprobar' : 'Denegar';
         	sameModal(apd + ' Cambio', '&#191;Quiere ' + apd.toLowerCase() + ' el cambio?', `admin.nicks.accion(${nid}, '${accion}', true)`);
	      } else sameFn('admin-nicks-change', { nid, accion }, `#nick_${nid}`);
	  	}
	},
	// SESIONES
	sesiones: {
	   borrar(sid, gew) {
         if(!gew){
         	sameModal('Cerrar sesi&oacute;n', '&#191;Quiere cerrar la sesi&oacute;n de este usuario/visitante? Se borrar&aacute; la sesi&oacute;n', `admin.sesiones.borrar(${sid}, true)`);
        	} else sameFn('posts-sesiones-borrar', `sesion_id=${sid}`, `#sesion_${sid}`);
      }
	},
	// TODOS LOS POSTS
	posts: {
	   borrar(postid, gew) {
         if(!gew){
         	sameModal('Borrar Post', '&#191;Quiere borrar este post permanentemente?', `admin.posts.borrar(${postid}, 1)`);		
        	} else sameFn('posts-admin-borrar', { postid }, `#post_${postid}`);
      }
	},
	// LISTA NEGRA
	blacklist: {
	   borrar(bid, gew) {
         if(!gew) {
         	sameModal('Retirar Bloqueo', '&#191;Quiere retirar este bloqueo?', `admin.blacklist.borrar(${bid}, true)`);
        	} else sameFn('admin-blacklist-delete', { bid }, `#block_${bid}`)
   	}
	},
	// CENSURAS
	badwords: {
	   borrar(wid, gew) {
         if(!gew){
         	sameModal('Retirar Filtro', '&#191;Quiere retirar este filtro?', `admin.badwords.borrar(${wid}, true)`);
         } else sameFn('admin-badwords-delete', { wid }, `#wid_${wid}`)
	   }
	},
	// TODAS LAS FOTOS
	fotos: {
	   borrar(foto_id, gew) {
         if(!gew){
         	sameModal('Borrar Foto', '&#191;Quiere borrar esta foto permanentemente?', `admin.badwords.borrar(${foto_id}, true)`);
         } else sameFn('admin-foto-borrar', { foto_id }, `#foto_${foto_id}`)
	   },
	   // Cerramos o Abrimos los comentario en foto
	   setOpenClosed(fid) {
	   	loading.start()
         $.post(ZCodeApp.url +'/admin-foto-setOpenClosed.php', { fid }, h => {
         	let number = parseInt(h.charAt(0));
         	if(number === 0) UPModal.alert('Error', h.substring(3));
         	let color = number ? 'red' : 'green';
         	let text = number ? 'Cerrados' : 'Abiertos';
         	$('#comments_foto_' + fid).html(`<font color="${color}">${text}</font>`);
         	loading.end()
         });
      },
      // Ocultamos | Mostramos la foto
      setShowHide(fid) {
         loading.start()
         $.post(ZCodeApp.url +'/admin-foto-setShowHide.php', { fid }, h => {
         	let number = parseInt(h.charAt(0));
         	if(number === 0) UPModal.alert('Error', h.substring(3));
         	let color = number ? 'purple' : 'green';
         	let text = number ? 'Oculta' : 'Visible';
         	$('#status_foto_' + fid).html(`<font color="${color}">${text}</font>`);
         	loading.end()
         });
      }
	},
	// TODAS LAS MEDALLAS
	medallas : {
	   borrar(medal_id, gew) {
	   	if(!gew) {
	   		sameModal('Borrar Medalla', '&#191;Quiere borrar esta medalla?', `admin.medallas.borrar(${medal_id}, 2)`);
		  	} else if(gew == '2') {
	   		sameModal('Borrar Medalla', 'Si borra la medalla, los usuarios que tengan esta medalla la perder&aacute;n, &#191;seguro que quiere continuar?', `admin.medallas.borrar(${medal_id}, 3)`);
	   	} else sameFn('admin-medalla-borrar', { medal_id }, `#medal_id_${medal_id}`)
   	},   
   	borrar_asignacion(aid, medal_id, gew) {
         if(!gew) {
	   		sameModal('Borrar Asignacion', '&#191;Quiere continuar borrando esta asignaci&oacute;n?', `admin.medallas.borrar_asignacion(${aid}, ${medal_id}, true)`);
       	} else sameFn('admin-medallas-borrar-asignacion', { aid, medal_id }, `#assign_id_${medal_id}`)
      },
	   asignar(medal_id, gew) {
	   	if(!gew){
	   		var form = `<div id="AFormInputs">
	   			<div class="form-line">
	   				<label for="m_usuario">Al usuario (nombre):</label>
	   				<input name="m_usuario" id="m_usuario"/><br />
	   				<label for="m_post">Al post (id):</label>
	   				<input name="m_post" id="m_post"/><br />
	   				<label for="m_foto">A la foto (id):</label>
	   				<input name="m_foto" id="m_foto"/>
	   			</div>
	   		</div>`;
	   		sameModal('Asignar medalla', form, `admin.medallas.asignar(${medal_id}, true)`);
		 	} else {
				loading.start()
				var params = [
					'mid=' + medal_id,
					'm_usuario=' + $('#m_usuario').val(),
					'pid=' + $('#m_post').val(),
					'fid=' + $('#m_foto').val()
				].join('&');
				$.post(ZCodeApp.url + '/admin-medalla-asignar.php', params, c => {
					UPModal.alert((c.charAt(0) == '0' ? 'Opps!' : 'Hecho'), c.substring(3), false);
			   	if(c.charAt(0) != '0') {
						var nmeds = parseInt($('#total_med_assig_' + medal_id).text());
						$('#total_med_assig_' + medal_id).text(nmeds + 1);
	               loading.end()
					}
				});
			}
	   }
   },
   // TODOS LOS USUARIOS
   users: {
		setInActive(uid) {
			loading.start()
			$.post(ZCodeApp.url +'/admin-users-InActivo.php', { uid }, h => {
   			let number = parseInt(h.charAt(0));
				if(number === 0) UPModal.alert('Error', h.substring(3));
				let color = (number === 1) ? 'green' : 'purple';
				let text = (number === 1) ? 'A' : 'Ina';
				$('#status_user_' + uid).html(`<font color="${color}">${text}ctivo</font>`);
		      loading.end()
		      loading.end()
			});
		}
   }
}

/* AFILIADOS */
var ad_afiliado = {
   cache: {},
   detalles: (aid) => {
   	$.post(ZCodeApp.url + '/afiliado-detalles.php', 'ref=' + aid, response => {
		   UPModal.setModal({
				title: 'Detalles del Afiliado',
				body: response,
				buttons: {
					confirmTxt: 'Aceptar',
					cancelShow: false
				}
			});
   	}); 
   }
}

function lastCommits() {
	$.post(ZCodeApp.url + '/github-last-commits.php', req => {
		console.log(req)
	});
}

$(document).ready(() => {
	const { url } = ZCodeApp;
	const selectJquery = $(".up-select--jquery");
	selectJquery.on('change', () => {
		if(selectJquery.val().length > 0) $('#ai_met_welcome, #desc_message_welcome').slideDown();
	});
	//
	let redirectURI = $('#redirect_uri');
	if(empty(redirectURI.val())) redirectURI.val(`${url}/discord.php`)
   $('#social_name').on('change', () => {
   	let replace = $('#social_name option:selected').val() ;
   	redirectURI.val(`${url}/${replace}.php`);
   });
   $("#botonCopiar").on("click", () => {
      redirectURI.select();
      document.execCommand("copy");
      window.getSelection().removeAllRanges();
      redirectURI.parent().find('small').html("Redirect URL ha sido copiado correctamente!");
      setTimeout(() => redirectURI.parent().find('small').html(''), 5000);
   });

   if(typeof preview !== 'undefined' && preview) {
    	$('#titulo').on('keyup', () => $('.result .title').html($('#titulo').val()))
      $('#descripcion').on('keyup', () => $('.result .description').html($('#descripcion').val()))
      $('#image').on('keyup', () => $('.result .image').attr({ src: $('#image').val() }))
   }

   if($('input[name="tables[all]"]').length) {
   	$('input[type="checkbox"][value="all"]').change(function() {
	    	if ($(this).prop('checked')) {
	      $('input[type="checkbox"]').prop('checked', true);
	    	} else {
	        $('input[type="checkbox"]').prop('checked', false);
	   	}
		});
   }

   $('#uploadForm').on('submit', function(e) {
      e.preventDefault();
      var formData = new FormData(this);
      $('#uploadForm button').html('Generando...');
      $.ajax({
         url: `${ZCodeApp.url}/admin-upload-favicon.php`,
         type: 'POST',
         data: formData,
         contentType: false,
         processData: false,
         success: function(response) {
         	let typeAct = parseInt(response.charAt(0));
         	let typeMsg = response.substring(3);
         	UPModal.alert((typeAct === 1 ? 'Bien' : 'Error'), typeMsg, true);
         	$('#uploadForm button').html('Subir Imagen');
         },
         error: function() {
         	UPModal.alert('Error', 'Error al subir la imagen.', false);
            $('#uploadForm button').html('Subir Imagen');
         }
      });
   });

});