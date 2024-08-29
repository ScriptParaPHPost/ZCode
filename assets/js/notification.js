export function permission() {
	if (!('Notification' in window)) {
      console.log('Este navegador no soporta notificaciones de escritorio.');
      if (typeof callback === 'function') {
         callback('unsupported');
      }
      return;
   }
   if (Notification.permission === 'default' || Notification.permission === 'undefined') {
      Notification.requestPermission().then(permission => {
         if (permission === 'granted') {
            console.log('Permiso de notificación concedido.');
         } else {
            console.log('Permiso de notificación denegado.');
         }
      });
   }
}