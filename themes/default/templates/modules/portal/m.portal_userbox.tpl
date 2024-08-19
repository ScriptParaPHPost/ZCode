<div class="p-2 d-flex justify-content-start align-items-center column-gap-2">
	<a href="{$tsConfig.url}/perfil/{$tsUser->nick}" class="avatar avatar-10 d-block rounded">
		<img title="Ver tu perfil" class="avatar" src="{$tsUser->use_avatar}"/>
	</a>
	<div class="">
		<a href="{$tsConfig.url}/perfil/{$tsUser->nick}" class="fs-5 text-decoration-none fw-semibold">{$tsUser->nick}</a>
	</div>
</div>
					<div id="user_box" class="post-autor vcard">
							<div class="avatarBox" style="margin-bottom:0">
								
						</div>
								
								<hr class="divider"/>
								<div class="tools">
									 <a href="{$tsConfig.url}/monitor/" class="systemicons monitor">Notificaciones (<strong>{$tsNots}</strong>)</a>
									 <a href="{$tsConfig.url}/mensajes/" class="systemicons mps">Mensajes nuevos (<strong>{$tsMPs}</strong>)</a>
									 <hr class="divider"/>
									 <a href="{$tsConfig.url}/agregar/" style="background:url({$tsConfig.images}/icons/posts.png) no-repeat left center;">Agregar post</a>
									 <a href="{$tsConfig.url}/fotos/agregar.php" style="background:url({$tsConfig.images}/icons/photo.png) no-repeat left center;">Agregar foto</a>
									 <hr class="divider"/>
									 <a href="{$tsConfig.url}/cuenta/" class="systemicons micuenta">Editar mi cuenta</a>
									 <a href="{$tsConfig.url}/login-salir.php" class="salir">Cerrar sesi&oacute;n</a>
								</div>
						  </div>