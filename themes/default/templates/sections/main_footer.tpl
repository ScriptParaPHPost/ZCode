			</section>
			<footer>
				<div class="links p-2">
					<div class="links-left py-1 d-flex justify-content-center align-items-center column-gap-2">
						<a class="text-decoration-none fw-semibold hover:main-bg-color active:main-bg-color" rel="internal" href="{$tsConfig.url}/pages/ayuda/" title="Ayuda">Ayuda</a>
						<a class="text-decoration-none fw-semibold hover:main-bg-color active:main-bg-color" rel="internal" href="{$tsConfig.url}/pages/chat/" title="Chat">Chat</a>
						<a class="text-decoration-none fw-semibold hover:main-bg-color active:main-bg-color" rel="internal" href="{$tsConfig.url}/pages/contacto/" title="Contacto">Contacto</a>  
						<a class="text-decoration-none fw-semibold hover:main-bg-color active:main-bg-color" rel="internal" href="{$tsConfig.url}/pages/protocolo/" title="Protocolo">Protocolo</a>
					</div>
					<div class="links-right py-1 d-flex justify-content-center align-items-center column-gap-2">
						<a class="text-decoration-none fw-semibold hover:main-bg-color active:main-bg-color" rel="internal" href="{$tsConfig.url}/pages/terminos-y-condiciones/" title="T&eacute;rminos y condiciones">T&eacute;rminos y condiciones</a>
						<a class="text-decoration-none fw-semibold hover:main-bg-color active:main-bg-color" rel="internal" href="{$tsConfig.url}/pages/privacidad/" title="Privacidad de datos">Privacidad de datos</a>
						<a class="text-decoration-none fw-semibold hover:main-bg-color active:main-bg-color" rel="internal" href="{$tsConfig.url}/pages/dmca/" title="Report Abuse - DMCA">Report Abuse - DMCA</a>
					</div>
				</div>
				<div class="footer-copyright text-center translucent-bg text-uppercase border-top small py-3">
					<a class="text-decoration-none fw-semibold hover:main-bg-color active:main-bg-color" href="{$tsConfig.url}" rel="internal" title="{$tsConfig.titulo} - {$tsConfig.slogan}">{$tsConfig.titulo}</a> &copy; {$smarty.now|date_format:"Y"}
				</div>
				<template id="verification-install">
					<p>Esto es solamente para verificar tú versión con la versión actual.</p>
					<p>Si remueves esto, no recibirás información sobre actualizaciones y cambios!</p>
					<input type="hidden" name="verification-code" value="{$tsVerification}">
				</template>
			</footer>
		</main>
	</div>
	<a class="irCielo position-fixed d-inline-block rounded shadow z-99 main-bg main-color d-flex justify-content-center align-items-center" href="#cielo" title="Ir al cielo">{uicon name="pull_up"}</a>


{if $tsUser->is_admod && $tsConfig.c_see_mod && $tsNovemods.total}
	<div id="stickymsg" class="position-fixed py-1 px-3 small toast-box toast-box--danger fw-semibold" style="cursor:default;">Hay <span class="fw-bold">{$tsNovemods.total} contenido{if $tsNovemods.total != 1}s{/if}</span> esperando revisi&oacute;n</div>
{/if}
{zCode js=["acciones.js"] scriptGlobal=true more=true}
<script>
$(document).ready(() => {
	notifica.popup({$tsNots});
	mensaje.popup({$tsMPs});
});
</script>
</body>
</html>