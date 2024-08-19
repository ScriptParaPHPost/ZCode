function handleLoadTab(type) {
	$('#portal_content > div.showHide').hide();
	// CARGAMOS
	let status = $('#portal_' + type).attr('status');
	$('#portal_' + type).show();
	if(status != 'activo') {
		//portal.posts_page(type, 1, false);
		imported('portal/portal-page.js', 'handlePostPage', { type, page: 1, scroll: false });
	}
}

export function handleLoadTabs({ obj, classObj }) {
	const tab = obj.attr('tab');
	$(classObj).removeClass('selected');
	obj.addClass('selected');
	// Aplicamos algunos efectos
	$('#portal_content > div').fadeOut();
	$('#portal_load').fadeIn();
	// Cargamos contenido de dicho tab!
	loading.start();
	handleLoadTab(tab);
}