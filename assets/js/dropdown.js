$(document).ready(() => {	
	$('a[data-dropopen]').on('click', function(event) {
		event.preventDefault();
		const dropopen = $(this).data('dropopen');
		const dropdownElement = $(`.up-dropdown[data-dropname="${dropopen}"]`);
		let isTrue = dropdownElement.attr('data-dropdown') === 'true';
		// Cerramos todos los dropdown abiertos
		$('.up-dropdown').attr('data-dropdown', false);
		// Alternar el estado del dropdown específico
		if (!isTrue) dropdownElement.attr('data-dropdown', true);
	});

	$('[data-dropaction]').on('click', function(event) {
		let dropAction = $(this).data('dropaction');
		$('.up-subdropdown')[(dropAction ? 'addClass' : 'removeClass')]('show');
		/**
		 *  Añadir height automatico
		*/
		let totalItems = $('.up-subdropdown .subitem-drop').length;
		let firstHeight = $('.up-subdropdown .subitem-drop').first().height();
    	let Height = (Math.ceil(firstHeight) * totalItems) + ((0.5 * 16) * totalItems) + 'px'; /* 16px root */
  		const style = {
  			height: dropAction ? Height : 'auto',
			transition: 'height .4s ease-in-out'
  		}
		$('.up-dropdown--secondary').css(style);
	});
});