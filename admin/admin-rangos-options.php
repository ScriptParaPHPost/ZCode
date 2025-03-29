<?php


$rangosPermisos = $tsRango['permisos'] ?? [];

$options = [
	"Super Moderaci&oacute;n" => [
		[
			'id' => "suad",
			'name' => "superadmin",
			'checked' => $rangosPermisos['suad'] ?? 0,
			'label' => "Super Admin",
			'optional' => "Si marca esto, los permisos p&uacute;blicos, de administraci&oacute;n y de moderaci&oacute;n estar&aacute;n inclu&iacute;dos"
		], [
			'id' => "sumo",
			'name' => "supermod",
			'checked' => $rangosPermisos['sumo'] ?? 0,
			'label' => "Super Moderador",
			'optional' => "Si marca esto, todos los permisos p&uacute;blicos y de moderaci&oacute;n estar&aacute;n inclu&iacute;dos"
		]
	],
	"Global" => [
		[
			'id' => "godp",
			'name' => "global-darpuntos",
			'checked' => $rangosPermisos['godp'] ?? 0,
			'label' => "Puntuar Posts",
			'optional' => "Podr&aacute;n puntuar posts"
		], [
			'id' => "gopp",
			'name' => "global-publicarposts",
			'checked' => $rangosPermisos['gopp'] ?? 0,
			'label' => "Publicar Posts",
			'optional' => "Podr&aacute;n publicar posts"
		], [
			'id' => "gopcp",
			'name' => "global-publicarcomposts",
			'checked' => $rangosPermisos['gopcp'] ?? 0,
			'label' => "Publicar Comentarios en Posts",
			'optional' => "Podr&aacute;n publicar comentarios posts"
		], [
			'id' => "govpp",
			'name' => "global-votarposipost",
			'checked' => $rangosPermisos['govpp'] ?? 0,
			'label' => "Votar postivo",
			'optional' => "Podr&aacute;n votar positivamente comentarios de posts"
		], [
			'id' => "govpn",
			'name' => "global-votarnegapost",
			'checked' => $rangosPermisos['govpn'] ?? 0,
			'label' => "Votar negativo",
			'optional' => "Podr&aacute;n votar negativamente comentarios de posts"
		], [
			'id' => "goepc",
			'name' => "global-editarpropioscomentarios",
			'checked' => $rangosPermisos['goepc'] ?? 0,
			'label' => "Editar comentarios propios",
			'optional' => "Podr&aacute;n editar los comentarios que ellos hacen"
		], [
			'id' => "godpc",
			'name' => "global-eliminarpropioscomentarios",
			'checked' => $rangosPermisos['godpc'] ?? 0,
			'label' => "Eliminar comentarios propios",
			'optional' => "Podr&aacute;n eliminar los comentarios que ellos hacen"
		], [
			'id' => "gopf",
			'name' => "global-publicarfotos",
			'checked' => $rangosPermisos['gopf'] ?? 0,
			'label' => "Publicar Fotos",
			'optional' => "Podr&aacute;n publicar fotos"
		], [
			'id' => "gopcf",
			'name' => "global-publicarcomfotos",
			'checked' => $rangosPermisos['gopcf'] ?? 0,
			'label' => "Publicar Comentarios en Fotos",
			'optional' => "Podr&aacute;n publicar comentarios en fotos"
		], [
			'id' => "gorpap",
			'name' => "global-revisarposts",
			'checked' => $rangosPermisos['gorpap'] ?? 0,
			'label' => "Revisar Posts",
			'optional' => "Si marca esto, cuando publiquen un post, antes de ser p&uacute;blico ser&aacute;n revisados"
		], [
			'id' => "govwm",
			'name' => "global-vermantenimiento",
			'checked' => $rangosPermisos['govwm'] ?? 0,
			'label' => "Acceso en mantenimiento",
			'optional' => "Podr&aacute;n navegar normalmente mientras la web est&aacute; en mantenimiento"
		]
	],
	"Panel de moderaci&oacute;n" => [
		[
			'id' => "moacp",
			'name' => "mod-accesopanel",
			'checked' => $rangosPermisos['moacp'] ?? 0,
			'label' => "Acceso al Panel de Moderaci&oacute;n",
			'optional' => "Podr&aacute;n entrar al panel de moderaci&oacute;n y ver posts y fotos denunciadas"
		]
	],
	"Denuncias" => [
		[
			'id' => "mocdu",
			'name' => "mod-cancelardenunciasusuarios",
			'checked' => $rangosPermisos['mocdu'] ?? 0,
			'label' => "Cancelar denuncias de usuarios",
			'optional' => "Podr&aacute;n ver y cancelar reportes de usuarios"
		], [
			'id' => "mocdf",
			'name' => "mod-cancelardenunciasfotos",
			'checked' => $rangosPermisos['mocdf'] ?? 0,
			'label' => "Cancelar denuncias de fotos",
			'optional' => "Podr&aacute;n rechazar reportes de fotos"
		], [
			'id' => "mocdp",
			'name' => "mod-cancelardenunciasposts",
			'checked' => $rangosPermisos['mocdp'] ?? 0,
			'label' => "Cancelar denuncias de posts",
			'optional' => "Podr&aacute;n rechazar reportes de posts"
		], [
			'id' => "moadm",
			'name' => "mod-aceptardenunciasmensajes",
			'checked' => $rangosPermisos['moadm'] ?? 0,
			'label' => "Aceptar denuncias de mensajes",
			'optional' => "Podr&aacute;n aceptar reportes de mensajes"
		], [
			'id' => "mocdm",
			'name' => "mod-cancelardenunciasmensajes",
			'checked' => $rangosPermisos['mocdm'] ?? 0,
			'label' => "Cancelar denuncias de mensajes",
			'optional' => "Podr&aacute;n rechazar reportes de mensajes"
		], [
			'id' => "movub",
			'name' => "mod-verusuariosbaneados",
			'checked' => $rangosPermisos['movub'] ?? 0,
			'label' => "Usuarios baneados",
			'optional' => "Podr&aacute;n ver usuarios baneados"
		], [
			'id' => "moub",
			'name' => "mod-usarbuscador",
			'checked' => $rangosPermisos['moub'] ?? 0,
			'label' => "Usar el buscador",
			'optional' => "Podr&aacute;n usar el buscador de contenidos"
		], [
			'id' => "morp",
			'name' => "mod-reciclajeposts",
			'checked' => $rangosPermisos['morp'] ?? 0,
			'label' => "Papelera de posts",
			'optional' => "Podr&aacute;n ver la papelera de reciclaje de posts y los posts eliminados"
		], [
			'id' => "morf",
			'name' => "mod-reciclajeposts",
			'checked' => $rangosPermisos['morf'] ?? 0,
			'label' => "Papelera de fotos",
			'optional' => "Podr&aacute;n ver la papelera de reciclaje de fotos y las fotos eliminadas"
		], [
			'id' => "mocp",
			'name' => "mod-reficlajefotos",
			'checked' => $rangosPermisos['mocp'] ?? 0,
			'label' => "Posts desaprobados",
			'optional' => "Podr&aacute;n ver la secci&oacute;n y los posts ocultos"
		], [
			'id' => "mocc",
			'name' => "mod-contenidocomentarios",
			'checked' => $rangosPermisos['mocc'] ?? 0,
			'label' => "Comentarios desaprobados",
			'optional' => "Podr&aacute;n ver los comentarios ocultos"
		]
	],
	"Moderaci&oacute;n Parcial" => [
		[
			'id' => "most",
			'name' => "mod-sticky",
			'checked' => $rangosPermisos['most'] ?? 0,
			'label' => "Fijar Posts",
			'optional' => "Podr&aacute;n poner/quitar posts en sticky desde el formulario y el mismo post"
		], [
          'id' => "most",
          'name' => "mod-sticky",
          'checked' => $rangosPermisos['most'] ?? 0,
          'label' => "Fijar Posts",
          'optional' => "Podr&aacute;n poner/quitar posts en sticky desde el formulario y el mismo post"
      ], [
         'id' => "moayca",
         'name' => "mod-abrirycerrarajax",
         'checked' => $rangosPermisos['moayca'] ?? 0,
         'label' => "Abrir/Cerrar Posts Ajax",
         'optional' => "Podr&aacute;n abrir/cerrar posts r&aacute;pidamente desde el post."
      ], [
         'id' => "movcud",
         'name' => "mod-vercuentasdesactivadas",
         'checked' => $rangosPermisos['movcud'] ?? 0,
         'label' => "Ver cuentas desactivadas",
         'optional' => "Podr&aacute;n ver cuentas de usuarios desactivadas."
      ], [
         'id' => "movcus",
         'name' => "mod-vercuentassuspendidas",
         'checked' => $rangosPermisos['movcus'] ?? 0,
         'label' => "Ver cuentas baneadas",
         'optional' => "Podr&aacute;n ver cuentas de usuarios baneados."
      ], [
         'id' => "mosu",
         'name' => "mod-suspenderusuarios",
         'checked' => $rangosPermisos['mosu'] ?? 0,
         'label' => "Suspender Usuarios",
         'optional' => "Podr&aacute;n suspender usuarios desde formulario modal."
      ], [
         'id' => "modu",
         'name' => "mod-desbanearusuarios",
         'checked' => $rangosPermisos['modu'] ?? 0,
         'label' => "Desbanear Usuarios",
         'optional' => "Podr&aacute;n desbanear usuarios."
      ], [
         'id' => "moep",
         'name' => "mod-eliminarposts",
         'checked' => $rangosPermisos['moep'] ?? 0,
         'label' => "Eliminar Posts",
         'optional' => "Podr&aacute;n eliminar posts de otros usuarios."
      ], [
         'id' => "moedpo",
         'name' => "mod-editarposts",
         'checked' => $rangosPermisos['moedpo'] ?? 0,
         'label' => "Editar Posts",
         'optional' => "Podr&aacute;n editar posts de otros usuarios (requiere permiso publicar post)."
      ], [
         'id' => "moop",
         'name' => "mod-ocultarposts",
         'checked' => $rangosPermisos['moop'] ?? 0,
         'label' => "Ocultar Posts",
         'optional' => "Podr&aacute;n ocultar posts de otros usuarios."
      ], [
         'id' => "mocepc",
         'name' => "mod-comentarpostcerrado",
         'checked' => $rangosPermisos['mocepc'] ?? 0,
         'label' => "Comentarios en Post Cerrado",
         'optional' => "Podr&aacute;n comentar en posts cerrados."
      ], [
         'id' => "moedcopo",
         'name' => "mod-editarcomposts",
         'checked' => $rangosPermisos['moedcopo'] ?? 0,
         'label' => "Editar Comentarios de Posts",
         'optional' => "Podr&aacute;n editar comentarios de posts de otros usuarios."
      ], [
         'id' => "moaydcp",
         'name' => "mod-desyaprobarcomposts",
         'checked' => $rangosPermisos['moaydcp'] ?? 0,
         'label' => "Acciones de revisi&oacute;n",
         'optional' => "Aprobar/desaprobar comentarios en los posts y en la revisi&oacute;n de comentarios."
      ], [
         'id' => "moecp",
         'name' => "mod-eliminarcomposts",
         'checked' => $rangosPermisos['moecp'] ?? 0,
         'label' => "Eliminar Comentarios de Posts",
         'optional' => "Podr&aacute;n eliminar comentarios en posts de otros usuarios."
      ], [
         'id' => "moef",
         'name' => "mod-eliminarfotos",
         'checked' => $rangosPermisos['moef'] ?? 0,
         'label' => "Eliminar Fotos",
         'optional' => "Podr&aacute;n eliminar fotos de otros usuarios."
      ], [
         'id' => "moedfo",
         'name' => "mod-editarfotos",
         'checked' => $rangosPermisos['moedfo'] ?? 0,
         'label' => "Editar Fotos",
         'optional' => "Podr&aacute;n editar fotos de otros usuarios (requiere publicar foto)."
      ], [
         'id' => "moecf",
         'name' => "mod-eliminarcomfotos",
         'checked' => $rangosPermisos['moecf'] ?? 0,
         'label' => "Eliminar Comentarios de Fotos",
         'optional' => "Podr&aacute;n eliminar comentarios en fotos de otros usuarios."
      ], [
         'id' => "moepm",
         'name' => "mod-eliminarpubmuro",
         'checked' => $rangosPermisos['moepm'] ?? 0,
         'label' => "Eliminar Publicaciones de Muros",
         'optional' => "Podr&aacute;n eliminar publicaciones en muros de otros usuarios."
      ], [
         'id' => "moecm",
         'name' => "mod-eliminarcommuro",
         'checked' => $rangosPermisos['moecm'] ?? 0,
         'label' => "Eliminar Comentarios de Muros",
         'optional' => "Podr&aacute;n eliminar comentarios en muros de otros usuarios."
      ], [
         'id' => "moat",
         'name' => "mod-administrartickets",
         'checked' => $rangosPermisos['moat'] ?? 0,
         'label' => "Administrar tickets",
         'optional' => "Podr&aacute;n cambiar el estado de los tickets, responder los tickets."
      ], [
         'id' => "moet",
         'name' => "mod-eliminartickets",
         'checked' => $rangosPermisos['moet'] ?? 0,
         'label' => "Eliminar tickets",
         'optional' => "Podr&aacute;n eliminar los tickets de los usuarios."
      ]
    ]
];
