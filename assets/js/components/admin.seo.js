const favs = {
	add: () => {
		UPModal.setModal({
			title: 'Añadir favicon',
			body: `<div class="upform-group">
			<label class="upform-label" for="size">Tamaño</label>
				<div class="upform-group-input">
					<input class="upform-input" type="number" name="size" id="size" placeholder="16">
				</div>
			</div>`,
			buttons: {
				confirmTxt: `S&iacute;`,
				confirmAction: `favs.insert()`,
				cancelShow: true
			}
		});
	},
	insert: () => {
		let size = empty($('input#size').val()) ? 16 : $('input#size').val();
		let adfav = `/images/favicon/logo-${size}.webp`;
		const html = `<div class="input-group w-100 mb-3">
          <span class="input-group-text text-center d-block" style="width: 90px;" id="pixeles">${size}x${size}</span>
      	<input class="form-control" type="text" id="images" name="images[${size}]" value="${adfav}" />
      	<button type="button" class="btn btnOk" onclick="$(this).parent().remove()">Quitar</button>
      </div>`;
      $('#addFavs').append(html);
      UPModal.close();
	}
}

if(typeof preview !== 'undefined' && preview) {
 	$('#titulo').on('keyup', () => $('.result .title').html($('#titulo').val()))
   $('#descripcion').on('keyup', () => $('.result .description').html($('#descripcion').val()))
   $('#image').on('keyup', () => $('.result .image').attr({ src: $('#image').val() }))
}