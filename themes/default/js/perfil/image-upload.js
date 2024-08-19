export function handleImageUploadFn(file) {
	$('.shout__buttons').hide();
	if (file) {
		let formData = new FormData();
		formData.append('file', file);
		$.ajax({
			url: `${ZCodeApp.url}/upload-imagen.php`,
			type: 'POST',
			data: formData,
			dataType: 'json',
			processData: false,
			contentType: false,
			xhr: function() {
				let xhr = $.ajaxSettings.xhr();
				xhr.upload.onprogress = function(e) {
					if (e.lengthComputable) {
						let percent = Math.round((e.loaded / e.total) * 100);
						$('#progress').addClass('uploading');
						if (percent === 100) {
							$('#progress').remove()
							$('.input-append').append(`<div class="loading"></div>`);
						}
					}
				};
				return xhr;
			},
			success: function(response) {
				$('.input-append').html(`<input type="hidden" value="${response[0][1]}" name="ifoto" />`);
				muro.stream.adjuntar();
				$('.input-append').html('Adjuntando imagen, espere...')
			}
		});
	}
}