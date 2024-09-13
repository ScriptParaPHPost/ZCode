export function updateAvatarGif() {
   $('input[name="avatar_active"]').on('click', () => {
      const gif = $('input[name="avatar_gif"]').val();
      const active = $('input[name="avatar_active"]').prop('checked');
      $.post(`${ZCodeApp.url}/cuenta-avatar-gif.php`, { gif, active }).done(response => {
         $('.avatar-gif').attr('src', gif);
         $('.avatar_loader').each((index, img) => $(img).attr('src', active ? gif : avatar.current));
      }).fail(() => UPModal.alert('Error', 'No se pudo actualizar el avatar.', false));
   });
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