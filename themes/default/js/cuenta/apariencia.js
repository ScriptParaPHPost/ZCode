export function ColorScheme({ name, selected }) {
	const { url, colores, themes } = ZCodeApp;
   $.post(`${url}/cuenta-${name}.php`, { selected }).done(req => {
      if (req) {
         const attr = (name === 'color') ? '-color' : '';
         const data = (name === 'color') ? colores[selected] : themes[selected];
         $('html').attr('data-theme' + attr, data);
      }
   }).fail(() => UPModal.alert('Error', 'No se pudo actualizar el esquema de color.', false));
};

export function changeAvatar() {
   const avatarImg = $('#avatar-img');
   const avatarLoader = $('.avatar_loader');
   const avatarLoading = $('.avatar-loading');

   $('#more_avatar').on('mouseover', '[data-avatar] img', function() {
      const image = $(this).attr('src');
      avatarImg.hide();
      avatarImg.before(`<img width="120" height="120" alt="avatar 120" src="${image}" class="avatar-big" id="avatar-this"/>`);
      avatarLoader.attr('src', image);
   }).on('mouseout', '[data-avatar] img', function() {
      avatarImg.show();
      $('#avatar-this').remove();
      avatarLoader.attr('src', avatarImg.attr('src'));
   }).on('click', '[data-avatar] img', function() {
      avatarLoading.show();
      const imageGet = $(this).parent().data('avatar');
      $.post(`${ZCodeApp.url}/cuenta-avatar-change.php`, { image: imageGet }, function(src) {
         src += `?=vs${string_random(10)}`;
         avatarImg.add(avatarLoader).attr('src', src);
         avatarLoading.hide();
      });
   });
}