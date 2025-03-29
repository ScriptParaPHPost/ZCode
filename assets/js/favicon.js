$('#uploadForm').on('submit', function(e) {
   e.preventDefault();
   let formData = new FormData(this);
   $('#uploadForm button').html('Generando...');
   $.ajax({
      url: `${ZCodeApp.ajax}/admin-upload-favicon.php`,
      type: 'POST',
      data: formData,
      contentType: false,
      processData: false,
      success: function(response) {
      	let typeAct = parseInt(response.charAt(0));
      	let typeMsg = response.substring(3);
      	UPModal.alert((typeAct === 1 ? 'Bien' : 'Error'), typeMsg, true);
      	$('#uploadForm button').html('Subir Imagen');
      },
      error: function() {
      	UPModal.alert('Error', 'Error al subir la imagen.', false);
         $('#uploadForm button').html('Subir Imagen');
      }
   });
});