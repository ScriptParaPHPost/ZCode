var mod = {
	posts : {
		view(postid) {
			loading.start();
			$.post(`${ZCodeApp.url}/moderacion-posts.php?do=view`, { postid }, req => {
				UPModal.setModal({
					title: '...',
					body: req,
					buttons: {
						confirmShow: false,
						cancelShow: true,
						cancelTxt: 'Cerrar'
					}
				});
				loading.end();
			});
		},
		ocultar(pid) {
			const razon = $('#d_razon').val();
			if(razon.length < 1) {
				UPModal.alert('Error', 'Introduzca una raz&oacute;n');
				razon.focus();
				return;
			} else if(razon.length > 50) {
				UPModal.alert('Error', 'La raz&oacute;n debe tener menos de 50 letras.');
				razon.focus();
				return;
			} else {
				$.post(`${ZCodeApp.url}/moderacion-posts.php?do=ocultar`, { razon, pid }, req => {
					UPModal.alert((req.charAt(0) == '0' ? 'Opps!' : 'Hecho'), req.substring(3), true);
				});
			}
		},
		borrar(pid, redirect, aceptar) {
			if(!aceptar){
				loading.start();
				$.post(`${ZCodeApp.url}/moderacion-posts.php?do=borrar`, req => {
					UPModal.setModal({
						title: 'Borrar Post',
						body: req,
						buttons: {
							confirmTxt: 'Borrar',
							confirmAction: `mod.posts.borrar(${pid}, '${redirect}', 1);`,
							cancelShow: true,
							cancelTxt: 'Cancelar'
						}
					});
					loading.end();
				});
			} else {
				UPModal.proccess_start('Eliminando...');
				let params = 'postid=' + pid;
				params += '&razon=' + $('#razon').val();
				params += '&razon_desc=' + $('input[name=razon_desc]').val();
				if($('#send_b').prop('checked')){
					params += '&send_b=yes';
				}
				loading.start();
				$.post(`${ZCodeApp.url}/moderacion-posts.php?do=borrar`, params, req => {
					const modPostBMsg = req.substring(3);
					const modPostBAction = parseInt(req.charAt(0));
					if(modPostBAction === 0) UPModal.alert('Error', modPostBMsg);
					if(modPostBAction === 1) {
						mod.redirect({ 
							redirect, 
							id: pid,
							page: "posts",
							message: modPostBMsg, 
							time: 2000 
						});
					}
				})
				.done(() => {
					UPModal.proccess_end();
					loading.end();
				});
			}
		}
	},
	mps: {
		borrar(mpid, few) {
			if(!few) {
				UPModal.setModal({
					title: 'Borrar Mensaje',
					body: '&#191;Quiere eliminar <strong>toda</strong> la conversaci&oacute;n?',
					buttons: {
						confirmTxt: `Si eliminarla`,
						confirmAction: `mod.mps.borrar(${mpid}, 1)`,
						cancelShow: true
					}
				});
		  	} else {
		  		loading.start();
		  		$.post(ZCodeApp.url + '/moderacion-mps.php?do=borrar', { mpid }, function(a) {
		  			UPModal.alert((a.charAt(0) == '0' ? 'Opps!' : 'Hecho'), a.substring(3), false);
		  			$('#report_' + mpid).fadeOut();
		  			loading.end();
		  		});
		  	}
		}
	},
	fotos: {
		borrar(fid, redirect, aceptar) {
			if(!aceptar){
				$.post(`${ZCodeApp.url}/moderacion-fotos.php?do=borrar`, req => {
					UPModal.setModal({
						title: 'Borrar Foto',
						body: req,
						buttons: {
							confirmTxt: `Borrar Foto`,
							confirmAction: `mod.fotos.borrar(${fid}, '${redirect}', 1)`,
							cancelShow: true
						}
					});
				});
			} else {
				UPModal.proccess_start('Eliminando...');
				let params = 'fid=' + fid;
				params += '&razon=' + $('#razon').val()
				params += '&razon_desc=' + $('input[name=razon_desc]').val();
				loading.start();
				$.post(`${ZCodeApp.url}/moderacion-fotos.php?do=borrar`, params, req => {
					const modFototBMsg = req.substring(3);
					const modFototBAction = parseInt(req.charAt(0));
					if(modFototBAction === 0) UPModal.alert('Error', modFototBMsg);
					if(modFototBAction === 1) {
						mod.redirect({ 
							redirect, 
							id: fid,
							page: "fotos",
							message: modPostBMsg, 
							time: 2000 
						});
					}
					loading.end();
				})
				.done(() => {
					UPModal.proccess_end();
					loading.end();
				});
			}
		}
	},
	users: {
		action(uid, action, redirect) {
			let btn_txt = (action == 'aviso') ? 'Enviar' : 'Suspender';
			let titulo = (action == 'aviso') ? 'Enviar Aviso/Alerta' : 'Suspender usuario';
			mod.load_dialog('/moderacion-users.php?do=' + action, 'uid=' + uid, titulo, btn_txt, 'mod.users.set_' + action + '(' + uid + ', ' + redirect + ');');
		},
		set_aviso(uid, redirect) {
			let paramsaviso = 'uid=' + uid;
			paramsaviso += '&av_type=' + $('#mod_type').val();
			paramsaviso += '&av_subject=' + $('#mod_subject').val();
			paramsaviso += '&av_body=' + $('#mod_body').val();
			mod.send_data('/moderacion-users.php?do=aviso', paramsaviso, uid, redirect, 'users');
		},
		set_ban: function(uid, redirect) {
			let paramsban =  'uid=' + uid;
			paramsban += '&b_time=' + $('#mod_time').val();
			paramsban += '&b_cant=' + $('#mod_cant').val();
			paramsban += '&b_causa=' + $('#mod_causa').val();
			//
			mod.send_data('/moderacion-users.php?do=ban', paramsban, uid, redirect, 'users');
		}
	},
	load_dialog: function(url_get, url_data, titulo, btn_txt, fn_txt){
		loading.start();
		$.post(`${ZCodeApp.url}${url_get}`, url_data, req => {
			UPModal.setModal({
				title: titulo,
				body: req,
				buttons: {
					confirmTxt: btn_txt,
					confirmAction: fn_txt,
					cancelShow: true
				}
			});
			loading.end();
		});
	},
	send_data: function(url_post, url_data, id, redirect, type){
		loading.start();
		UPModal.proccess_start('Procesando...');
		$.post(`${ZCodeApp.url}/${url_post}`, url_data, req => {
			switch(req.charAt(0)){
				case '0': //Error
					UPModal.alert('Error', req.substring(3));
				break;
				case '1':
					mod.redirect({ 
						redirect, 
						id,
						page: type,
						message: req.substring(3), 
						time: 2000 
					});
				break;
			}
			loading.end();
		})
		.done(() => {
			UPModal.proccess_end();
			loading.end();
		});
	},
	reboot: function(id, type, hdo, redirect) {
		loading.start();
		$.post(`${ZCodeApp.url}/moderacion-${type}.php?do=${hdo}`, { id }, req => {
		 	switch(req.charAt(0)){
			  	case '0':
					UPModal.alert("Error", req.substring(3));
			  	break;
			  	case '1':
			  		UPModal.alert("Bien", req.substring(3));
					mod.redirect({ 
						redirect, 
						id,
						page: type,
						message: req.substring(3), 
						time: 2000 
					});
			  break;
		 }
		 loading.end();
		});
	},
	redirect: function({ redirect, id, page, message, time = 1200 }) {
		setTimeout(() => {
			if(redirect == 'true') document.location.href =`${ZCodeApp.url}/moderacion/${page}/`;
			else if(redirect == 'fotos') {
				UPModal.alert('Aviso', modFototBMsg);
				document.location.href = `${ZCodeApp.url}/${page}/`;
			} else {
				UPModal.close();
				$('#report_' + id).slideUp();   
			}
		}, time);
	}
}