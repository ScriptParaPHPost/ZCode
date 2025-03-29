export function bloquear({ user, bloqueado, lugar, aceptar }) {
	if(!aceptar && bloqueado) {
		loadModalBloquear({ user, lugar });
	}
	if(bloqueado) UPModal.proccess_start('Procesando...');
	loading.start();
	let data = 'user=' + user + (bloqueado ? '&bloquear=1' : '') + gget('key');
	$.post(`${ZCodeApp.url}/bloqueos-cambiar.php`, data, request => {
		UPModal.alert('Bloquear Usuarios', request.substring(3));
		if(parseInt(request.charAt(0)) === 1) {
			if(lugar === 'perfil' || lugar === 'mis_bloqueados' || lugar === 'mensajes') {
				loadLugar(lugar);
			}
			if(lugar === 'respuestas' || lugar === 'comentarios') {
				$('li.desbloquear_'+user)[(bloqueado ? 'show' : 'hide')]();
				$('li.bloquear_'+user)[(bloqueado ? 'hide' : 'show')]();
			}
		}
	})
	.fail(() => UPModal.error_500(`bloquear('${user}', '${bloqueado}', '${lugar}', true)`))
	.done(() => UPModal.proccess_end());
	loading.end();
}

function loadModalBloquear({ user, lugar }) {
	UPModal.setModal({
		title: 'Bloquear usuario',
		body: '&iquest;Realmente deseas bloquear a este usuario?',
		buttons: {
			confirmAction: `bloquear('${user}', true, '${lugar}', true)`,
			cancelShow: true
		}
	});
	return;
}

function loadLugar(lugar = '') {
	const actionText = bloqueado ? 'Desbloquear' : 'Bloquear';
	const baseClass = 'bloquearU';
	const toggleClass = bloqueado ? `des${baseClass}` : baseClass;
	const state = !bloqueado; // Alternar el estado booleano
	const divBox = (lugar !== 'mis_bloqueados') ? '#bloquear_cambiar' : '.bloquear_usuario_' + user;

	$(divBox).html(actionText).attr('onclick', `bloquear('${user}', ${state}, '${lugar}')`)
	if(lugar !== 'mensajes') {
		$(divBox).removeClass(`${baseClass} des${baseClass}`).addClass(toggleClass);
	}	
}