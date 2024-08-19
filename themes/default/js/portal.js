var portal = {
	// CARGAR CONTENIDO
	save_configs: function(){
		let inputs = $('#config_inputs :input');
		let cat_ids = [];
		inputs.each(function() {
			if($(this).prop('checked')) cat_ids.push($(this).val());
		});
		if(cat_ids == '') return false;
		//
		loading.start();
		$.post(`${ZCodeApp.url}/portal-posts_config.php`, 'cids=' + cat_ids, h => {
			switch(h.charAt(0)){
				case '0': //Error
					mydialog.alert('Error', h.substring(3));
				break;
				case '1': //OK
					$('#config_posts').slideUp();
					imported('portal/portal-page.js', 'handlePostPage', { type: 'posts', page: 1, scroll: false });
				break;
			}
			loading.end();
		});                
	}
}

/** READY **/
$(function(){
	const portalTabs = $('.userPortal--item');
	portalTabs.map( function(element, index) {
		$(this).on('click', function() {
			imported('portal/portal-tabs.js', 'handleLoadTabs', { obj: $(this), classObj: '.userPortal--item' });
		});
	});

	
	const allTimes = ['today', 'yesterday', 'month', 'old'];
	for(let acDatesCount = 0; acDatesCount < allTimes.length; acDatesCount++){
		let obj_name = 'last-activity-date-' + allTimes[acDatesCount];
		let obj = $('#' + obj_name);
		// MORE
		let m_name = 'more-' + allTimes[acDatesCount];
		const mobj = $('#' + m_name);
		// ACTIVO
		let active = obj.attr('active');
		// VALIDAMOS
		if(typeof active == 'undefined') {
			//
			let new_id = $(mobj).attr('nid');
			$(mobj).attr('id',new_id);
			$(mobj).find('h3').show();
			$(mobj).removeAttr('nid').attr('active','true');
			
		} else {
			$(mobj).find('h3').remove();
			const html = $(mobj).html();
			$(obj).append(html)
			$(mobj).remove();
		}
	}
});