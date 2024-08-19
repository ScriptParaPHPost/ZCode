export function updateAvatarGif() {
   const gif = $('input[name="avatar_gif"]').val();
   const active = $('input[name="avatar_active"]').prop('checked');
   $.post(`${ZCodeApp.url}/cuenta-avatar-gif.php`, { gif, active }).done(response => {
      $('.avatar-gif').attr('src', gif);
      $('.avatar_loader').each((index, img) => $(img).attr('src', active ? gif : avatar.current));
   }).fail(() => UPModal.alert('Error', 'No se pudo actualizar el avatar.', false));
};