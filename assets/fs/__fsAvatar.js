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

function eventMouseOver({event, avatarImg, avatarLoader}) {
   const image = $(event.currentTarget).attr('src');
   avatarImg.hide().before(`<img width="120" height="120" alt="avatar 120" src="${image}" class="avatar-big" id="avatar-this"/>`);
   avatarLoader.attr('src', image);
}

function eventMouseOut({avatarImg, avatarLoader}) {
   avatarImg.show();
   $('#avatar-this').remove();
   avatarLoader.attr('src', avatarImg.attr('src'));
}

function eventMouseClick({event, avatarImg, avatarLoading, avatarLoader}) {
   avatarLoading.show();
   const imageGet = $(event.currentTarget).parent().data('avatar') || $(event.currentTarget).parent().data('myavatar');

   $.post(`${ZCodeApp.url}/cuenta-avatar-change.php`, { image: imageGet }, function(src) {
    src += `?=vs${string_random(10)}`;
    avatarImg.add(avatarLoader).attr('src', src);
    avatarLoading.hide();
   });
}

export function changeAvatar() {
   const avatarImg = $('#avatar-img');
   const avatarLoader = $('.avatar_loader');
   const avatarLoading = $('.avatar-loading');
   const avatarItems = '[data-avatar] img, [data-myavatar] img';

    $('#more_avatar, #mis_avatares').on({
      mouseover: (event) => eventMouseOver({event, avatarImg, avatarLoader}),
      mouseout: () => eventMouseOut({avatarImg, avatarLoader}),
      click: event => eventMouseClick({event, avatarImg, avatarLoading, avatarLoader})
   }, avatarItems);
}

export function deleteAvatar() {
   const item = '[data-myavatar]';
   $(item).on('click', function() {
      let image = $(this).data('myavatar');
      console.log(image)
   })
}