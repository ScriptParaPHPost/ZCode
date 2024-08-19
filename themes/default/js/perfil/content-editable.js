export function handleContentEditable(div) {
	let placeholder = div.attr('placeholder');
	// Mostrar el placeholder al cargar la página si el div está vacío
	if (!div.val().length) div.attr({ placeholder });
	// Mostrar el texto de placeholder al enfocar el div si está vacío
	div.on('focus', function() {
		if ($(this).attr('placeholder') === placeholder) $(this).text('');
	}).on('blur', function() {
		if (!$(this).val().trim().length) $(this).attr({ placeholder });
	}).on('input', function() {
		if ($(this).attr('placeholder') !== placeholder) {
			$(this).removeClass('placeholder');
		}
	});
		
}
