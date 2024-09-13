export function executeSync(page, selected, object, tag = 'html') {
   $.post(`${ZCodeApp.url}/cuenta-${page}.php`, { selected }).done(req => {
      if (req) $(tag).attr(object);
   }).fail(() => UPModal.alert('Error', `No se pudo actualizar el esquema de ${page}.`, false));
};

export function syncThemeSystem() {
   $('#scheme').on('click', function() {
      const { themes } = ZCodeApp;
      let selected = ($('#scheme').prop('checked') === true) ? 1 : 0;
      executeSync('scheme', selected, { 'data-theme': themes[selected] });
   });
};

export function syncThemeColor() {
   const { url, colores } = ZCodeApp;
   const themesColor = $('.syncThemeColor');
   themesColor.map( (ncolor, check) => {
      $(check).on('click', function() {
         let selected = $(this).data('color');
         $('.syncThemeColor > div').removeClass('border');
         $('.syncThemeColor > .tc' + selected).addClass('border');
         executeSync('color', selected, { 'data-theme-color': colores[selected] });
         $('.customizar_tema').addClass('d-none');
         if(selected === 0) {
            $('.customizar_tema').removeClass('d-none');
            imported('cuenta/customizar.js', 'handleChangeColor');
         }
      });
   });
};

export function syncThemeFont() {
   const family = $('#font_family');
   const size = $('#font_size');
   family.on('change', function() {
      let selected = family.val();
      executeSync('family', selected, { 'data-font-family': selected }, 'body');
   });
   size.on('change', function() {
      let selected = size.val();
      executeSync('size', selected, { 'data-font-size': selected }, 'body');
   });
};