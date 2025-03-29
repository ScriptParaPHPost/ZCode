export function loadingStart() {
	const { images: { assets: pathImages } } = ZCodeApp;
	const optionLoading = {
		position: 'fixed',
		top: '1rem',
		left: '1rem',
		padding: '.325rem 1rem .325rem .5rem',
		zIndex: 9999,
		background: '#072853',
		borderRadius: '.325rem'
	}
	const optionContent = {
		display: 'flex',
		justifyContent: 'center',
		alignItems: 'center',
		flexDirection: 'row-reverse',
		gap: '.5rem',
		fontWeight: '700',
		color: '#FFF'
	}
	const { assets } = ZCodeApp;
	$.getJSON(`${assets}/icons/spinner.json`, data => {
		let svg = data['90-ring-with-bg']
		.replace('viewBox', 'fill="#FFF" viewBox')
		.replace('width="1.5rem"', 'width="1rem"')
		.replace('height="1.5rem"', 'height="1rem"');
		$('#loading_start div').append(svg);
	});
	$('body').append(`<div id="loading_start"><div>Procesando...</div></div>`);
	$('#loading_start').css(optionLoading);
	$('#loading_start').find('div').css(optionContent);
}