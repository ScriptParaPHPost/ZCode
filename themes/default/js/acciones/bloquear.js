export function bloquear({ user, bloqueado, lugar, aceptar }) {
	if(!aceptar && bloqueado) {
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
	if(bloqueado) UPModal.proccess_start('Procesando...');
	loading.start();
	let data = 'user=' + user + (bloqueado ? '&bloquear=1' : '') + gget('key');
	$.post(`${ZCodeApp.url}/bloqueos-cambiar.php`, data, h => {
		UPModal.alert('Bloquear Usuarios', h.substring(3));
		if(h.charAt(0) == 1){
			switch(lugar){
				case 'perfil':
				case 'mis_bloqueados':
					const actionText1 = bloqueado ? 'Desbloquear' : 'Bloquear';
					const baseClass = 'bloquearU';
					const toggleClass = bloqueado ? `des${baseClass}` : baseClass;
					const state1 = !bloqueado; // Alternar el estado booleano
					const divBox = (lugar == 'perfil') ? '#bloquear_cambiar' : '.bloquear_usuario_' + user;

					$(divBox)
					  .html(actionText1)
					  .attr('href', `javascript:bloquear('${user}', ${state1}, '${lugar}')`)
					  .removeClass(`${baseClass} des${baseClass}`)
					  .addClass(toggleClass);
				break;
				case 'respuestas':
				case 'comentarios':
					$('li.desbloquear_'+user)[(bloqueado ? 'show' : 'hide')]();
					$('li.bloquear_'+user)[(bloqueado ? 'hide' : 'show')]();
				break;
				case 'mensajes':
					const actionText = bloqueado ? 'Desbloquear' : 'Bloquear';
					const state = !bloqueado; // Alternar el estado booleano
					$('#bloquear_cambiar').html(actionText).attr('href', `javascript:bloquear('${user}', ${state}, '${lugar}')`);
				break;
			}
		}
	})
	.fail(() => UPModal.error_500(`bloquear('${user}', '${bloqueado}', '${lugar}', true)`))
	.done(() => UPModal.proccess_end());
	loading.end();
}