			</section>
			<footer>
				<div class="links p-2 d-flex d-lg-block justify-content-around align-items-center">
					<div class="links-left text-center text-lg-start py-1 d-block d-lg-flex justify-content-around justify-content-lg-center align-items-center column-gap-2">
						{foreach [
							["isPage" => "ayuda", "title" => "Ayuda"],
							["isPage" => "contacto", "title" => "Contacto"],
							["isPage" => "protocolo", "title" => "Protocolo"]
						] item=i}
							{include "LinkFoot.tpl" isPage=$i.isPage title=$i.title}
						{/foreach}
					</div>
					<div class="links-right text-center text-lg-start py-1 d-block d-lg-flex justify-content-around justify-content-lg-center align-items-center column-gap-2">
						{foreach [
							["isPage" => "terminos-y-condiciones", "title" => "T&eacute;rminos y condiciones"],
							["isPage" => "privacidad", "title" => "Privacidad de datos"],
							["isPage" => "dmca", "title" => "Report Abuse - DMCA"]
						] item=i}
							{include "LinkFoot.tpl" isPage=$i.isPage title=$i.title}
						{/foreach}
					</div>
				</div>
				<div class="footer-copyright text-center translucent-bg text-uppercase border-top small py-3">
					<a class="text-decoration-none fw-semibold hover:main-bg-color active:main-bg-color" href="{$tsConfig.url}" rel="internal" title="{$tsConfig.titulo} - {$tsConfig.slogan}">{$tsConfig.titulo}</a> &copy; {$smarty.now|date_format:"Y"} | versión: <strong>{SCRIPT_VERSION}</strong> - <a class="text-decoration-none d-block d-lg-inline fw-semibold hover:main-bg-color active:main-bg-color" rel="internal" href="{$tsConfig.url}/status.html">Estado operativo</a> - <a class="text-decoration-none d-block d-lg-inline fw-semibold hover:main-bg-color active:main-bg-color pt-3" rel="external" target="_blank" href="https://discord.gg/mx25MxAwRe">{uicon name="discord" folder="social" class="pe-none"}</a>
				</div>
				<template id="verification-install">
					<p>Esto es solamente para verificar tú versión con la versión actual.</p>
					<p>Si remueves esto, no recibirás información sobre actualizaciones y cambios!</p>
					<input type="hidden" name="script" value="{SCRIPT_NAME}">
					<input type="hidden" name="version" value="{SCRIPT_VERSION}">
					<input type="hidden" name="verification-code" value="{$tsVerification}">
				</template>
			</footer>
		</main>
	</div>
	<a class="irCielo position-fixed d-inline-block rounded shadow z-99 main-bg main-color d-flex justify-content-center align-items-center" href="#cielo" title="Ir al cielo">{uicon name="pull_up"}</a>

{if $tsUser->is_admod && $tsConfig.c_see_mod && $tsNovemods.total}
	<div id="stickymsg" class="position-fixed py-1 px-3 small toast-box toast-box--danger fw-semibold" style="cursor:default;">Hay <span class="fw-bold">{$tsNovemods.total} contenido{if $tsNovemods.total != 1}s{/if}</span> esperando revisi&oacute;n</div>
{/if}
<div class="message-version-new">Esta es una <strong>nueva versión V3</strong>, lo cual tendrá muchos problemas en el <strong>backend</strong>!</div>
</body>
</html>