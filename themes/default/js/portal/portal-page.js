const cache = {};
export function handlePostPage({ type, page, scroll }) {
	const { images: { assets: publicImagesPath } } = ZCodeApp;
	$('#portal_' + type + '_content').html(`<div class="py-3 w-100"><img class="mx-auto" src="${publicImagesPath}/loading_bar.gif" /></div>`);
	//
	if(scroll == true) $.scrollTo('#cuerpocontainer', 250);
	if(typeof cache[type + '_' + page] == 'undefined') {
		loading.start();
		$.get(`${ZCodeApp.url}/portal-${type}_pages.php?page=${page}`, req => {
			cache[`${type}_${page}`] = req;
			$(`#portal_${type}`).attr({ status: 'activo' });
			$(`#portal_${type}_content`).html(req);
			loading.end();
		});
	} else {
		$(`#portal_${type}_content`).html(cache[`${type}_${page}`]);
	}
	loading.end();
}