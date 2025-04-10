/**
 * Con estas funciones "configureAndShowModal()" y "postRequestWithModal()"
 * y de esta forma simplificamos
*/
function configureAndShowModal(title, body, action) {
	const buttons = {
		confirmTxt: 'Aceptar',
		confirmAction: action,
		cancelShow: true
	};
   UPModal.setModal({ title, body, buttons });
}
function postRequestWithModal(page, params, element, without = '') {
   loading.start();
   UPModal.proccess_start();
   let param = !empty(without) ? '' : `?from=dashboard`;
	$.post(`${ZCodeApp.url}/${page}.php${param}`, params, response => {
   	UPModal.proccess_end();
   	console.log(response)
   	let tpy = parseInt(response.charAt(0)) === 1;
   	UPModal.alert((tpy ? 'Hecho' : 'Opps!'), response.substring(3), false);
   	if(tpy) $(element).fadeOut().remove(); 
   	loading.end();
   });
}

const foro = {
	eliminar(fid, gew = true) {
      if(gew){
      	configureAndShowModal('Borrar Categoría', '&#191;Quiere borrar esta categoria?', `foro.eliminar(${fid}, false)`)
      } else {
      	postRequestWithModal('admin-eliminar-categoria', { fid }, `#few_${fid}`);
      }
	}
}

var admin = {
	// AFILIADOS
	afs: {
	   borrar(afid, gew) {
         if(!gew){
         	configureAndShowModal('Borrar Afiliado', '&#191;Quiere borrar este afiliado?', `admin.afs.borrar(${afid}, 1)`)
	      } else postRequestWithModal('afiliado-borrar', { afid }, `#few_${afid}`, 'afs');
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
		   $.post(ZCodeApp.url +'/admin-noticias-setInActive.php?from=dashboard', { nid }, req => {
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
         	configureAndShowModal('Eliminar Noticia', '&#191;Quiere eliminar la noticia?', `admin.news.borrar(${nid}, true)`);
         } else {
         	postRequestWithModal('admin-eliminar-noticia', { nid }, `[nid="${nid}"]`);
         }
		}
	},
	// NICKS
	nicks: {
	  	accion(nid, accion, gew) {
	    	if(!gew){
	    		apd = (accion == 'aprobar') ? 'Aprobar' : 'Denegar';
         	configureAndShowModal(apd + ' Cambio', '&#191;Quiere ' + apd.toLowerCase() + ' el cambio?', `admin.nicks.accion(${nid}, '${accion}', true)`);
	      } else postRequestWithModal('admin-nicks-change', { nid, accion }, `#nick_${nid}`);
	  	}
	},
	// SESIONES
	sesiones: {
	   borrar(sid, gew) {
         if(!gew){
         	configureAndShowModal('Cerrar sesi&oacute;n', '&#191;Quiere cerrar la sesi&oacute;n de este usuario/visitante? Se borrar&aacute; la sesi&oacute;n', `admin.sesiones.borrar(${sid}, true)`);
        	} else postRequestWithModal('posts-sesiones-borrar', `sesion_id=${sid}`, `#sesion_${sid}`);
      }
	},
	// TODOS LOS POSTS
	posts: {
	   borrar(postid, gew) {
         if(!gew){
         	configureAndShowModal('Borrar Post', '&#191;Quiere borrar este post permanentemente?', `admin.posts.borrar(${postid}, 1)`);		
        	} else postRequestWithModal('posts-admin-borrar', { postid }, `#post_${postid}`);
      }
	},
	// LISTA NEGRA
	blacklist: {
	   borrar(bid, gew) {
         if(!gew) {
         	configureAndShowModal('Retirar Bloqueo', '&#191;Quiere retirar este bloqueo?', `admin.blacklist.borrar(${bid}, true)`);
        	} else postRequestWithModal('admin-blacklist-delete', { bid }, `#block_${bid}`)
   	}
	},
	// CENSURAS
	badwords: {
	   borrar(wid, gew) {
         if(!gew){
         	configureAndShowModal('Retirar Filtro', '&#191;Quiere retirar este filtro?', `admin.badwords.borrar(${wid}, true)`);
         } else postRequestWithModal('admin-badwords-delete', { wid }, `#wid_${wid}`)
	   }
	},
	// TODAS LAS FOTOS
	fotos: {
	   borrar(foto_id, gew) {
         if(!gew){
         	configureAndShowModal('Borrar Foto', '&#191;Quiere borrar esta foto permanentemente?', `admin.badwords.borrar(${foto_id}, true)`);
         } else postRequestWithModal('admin-foto-borrar', { foto_id }, `#foto_${foto_id}`)
	   },
	   // Cerramos o Abrimos los comentario en foto
	   setOpenClosed(fid) {
	   	loading.start()
         $.post(ZCodeApp.url +'/admin-foto-setOpenClosed.php?from=dashboard', { fid }, h => {
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
         $.post(ZCodeApp.url +'/admin-foto-setShowHide.php?from=dashboard', { fid }, h => {
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
	   		configureAndShowModal('Borrar Medalla', '&#191;Quiere borrar esta medalla?', `admin.medallas.borrar(${medal_id}, 2)`);
		  	} else if(gew === '2') {
	   		configureAndShowModal('Borrar Medalla', 'Si borra la medalla, los usuarios que tengan esta medalla la perder&aacute;n, &#191;seguro que quiere continuar?', `admin.medallas.borrar(${medal_id}, 3)`);
	   	} else postRequestWithModal('admin-medalla-borrar', { medal_id }, `#medal_id_${medal_id}`)
   	},   
   	borrar_asignacion(aid, medal_id, gew) {
         if(!gew) {
	   		configureAndShowModal('Borrar Asignacion', '&#191;Quiere continuar borrando esta asignaci&oacute;n?', `admin.medallas.borrar_asignacion(${aid}, ${medal_id}, true)`);
       	} else postRequestWithModal('admin-medallas-borrar-asignacion', { aid, medal_id }, `#assign_id_${medal_id}`)
      },
	   asignar(medal_id, gew) {
	   	if(!gew){
	   		var form = `<div id="AFormInputs">
	   			<div class="upform-group">
	   				<label class="upform-label" for="m_usuario">Al usuario (nombre):</label>
	   				<div class="upform-group-input"><input class="upform-input" name="m_usuario" id="m_usuario"/></div>
	   			</div>
	   			<div class="upform-group">
	   				<label class="upform-label" for="m_post">Al post (id):</label>
	   				<div class="upform-group-input"><input class="upform-input" name="m_post" id="m_post"/></div>
	   			</div>
	   			<div class="upform-group">
	   				<label class="upform-label" for="m_foto">A la foto (id):</label>
	   				<div class="upform-group-input"><input class="upform-input" name="m_foto" id="m_foto"/></div>
	   			</div>
	   		</div>`;
	   		configureAndShowModal('Asignar medalla', form, `admin.medallas.asignar(${medal_id}, true)`);
		 	} else {
				loading.start()
				var params = [
					'mid=' + medal_id,
					'm_usuario=' + $('#m_usuario').val(),
					'pid=' + $('#m_post').val(),
					'fid=' + $('#m_foto').val()
				].join('&');
				$.post(ZCodeApp.url + '/admin-medalla-asignar.php?from=dashboard', params, c => {
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
			$.post(ZCodeApp.url +'/admin-users-InActivo.php?from=dashboard', { uid }, h => {
   			let number = parseInt(h.charAt(0));
				if(number === 0) UPModal.alert('Error', h.substring(3));
				let color = (number === 1) ? 'green' : 'purple';
				let text = (number === 1) ? 'A' : 'Ina';
				$('#status_user_' + uid).html(`<font color="${color}">${text}ctivo</font>`);
		      loading.end();
			});
		}
   }
}

var packs = {
  	reload(path) {
  		location.href = ZCodeApp.url + '/admin/packs?act=abrir&path=' + path;
  	},
   subir(path) {
      let formData = new FormData();
      formData.append('file', $('#image')[0].files[0]);
      formData.append('path', $('#path').val());
      $.ajax({
         url: `${ZCodeApp.url}/admin-subir-icono.php?from=dashboard`,
         type: 'post',
         data: formData,
         contentType: false,
         processData: false,
         success: response => {
            switch(response.charAt(0)) {
               case '0':
                  UPModal.alert('Error', response.substring(3), false);
               break;
               case '1':
               	UPModal.setModal({
							title: 'Bien',
							body: response.substring(3),
							buttons: {
								confirmTxt: 'Continuar',
								confirmAction: `packs.reload(${path})`,
								cancelShow: false
							}
						});
               break;
            }
         }
      });
      return false;
   },
   borrar(carpeta, hash, status) {
      if(!status) {
         UPModal.setModal({
				title: '¿Deseas eliminar ' + (carpeta == 'med' ? 'estos iconos' : 'este icono') + '?',
				body: 'Esto eliminará el/los iconos de su tema',
				buttons: {
					confirmTxt: 'Continuar',
					confirmAction: `packs.borrar('${carpeta}', '${hash}', true)`
				}
			});
			return;
      } 
      let params = ['path=' + carpeta, 'hash=' + hash].join('&')
      $.post(`${ZCodeApp.url}/admin-eliminar-icono.php?from=dashboard`, params, del => {
         UPModal.close();
         if(del) {
            if(carpeta === 'medallas') {
               $("tr." + hash).each( (inx, trh) => trh.remove())
            } else $("tr." + hash).remove()
         }
      })
   }
}

$(document).ready(() => {

	const { url } = ZCodeApp;
	const selectJquery = $(".up-select--jquery");
	selectJquery.on('change', () => {
		if(selectJquery.val().length > 0) $('#ai_met_welcome, #desc_message_welcome').slideDown();
	});
	//

   if($('input[name="tables[all]"]').length) {
   	$('input[type="checkbox"][value="all"]').change(function() {
	    	if ($(this).prop('checked')) {
	      	$('input[type="checkbox"]').prop('checked', true);
	    	} else {
	        $('input[type="checkbox"]').prop('checked', false);
	   	}
		});
   }

   $('#change_theme').on('change', function(e) {
   	let tema = $(this).val();
   	$.post(`${ZCodeApp.url}/admin-tema.php?from=dashboard`, { tema }, req => {
         UPModal.alert((req.charAt(0) === '1' ? 'Bien' : 'Error'), req.substring(3), true);
         if(req.charAt(0) === '1') {
         	$('#tema_actual').html(tema);
         }
   	});
   });

});