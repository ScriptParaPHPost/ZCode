<section class="up-card">
	<div class="up-card--header" icon="false">
		<div class="up-header--title">
			<span>Men&uacute;</span>
		</div>
	</div>
	<div class="up-card--body">
		<div id="mp-menu" class="cat-list">
			<a class="d-block text-decoration-none fw-semibold py-2 px-3 rounded hover:main-bg hover:main-color active:main-bg active:main-color my-2 mp_inbox{if $tsAction == ''} main-bg main-color{/if}" href="{$tsConfig.url}/mensajes/">Recibidos</a>
			<a class="d-block text-decoration-none fw-semibold py-2 px-3 rounded hover:main-bg hover:main-color active:main-bg active:main-color my-2 mp_send{if $tsAction == 'enviados'} main-bg main-color{/if}" href="{$tsConfig.url}/mensajes/enviados/">Enviados</a>
			<a class="d-block text-decoration-none fw-semibold py-2 px-3 rounded hover:main-bg hover:main-color active:main-bg active:main-color my-2 mp_return{if $tsAction == 'respondidos'} main-bg main-color{/if}" href="{$tsConfig.url}/mensajes/respondidos/">Respondidos</a>
			<div class="divider"></div>
			{if $tsAction == 'search'}
				<span class="d-block text-decoration-none fw-semibold py-2 px-3 rounded hover:main-bg hover:main-color active:main-bg active:main-color my-2 mp_search main-bg main-color">Resultados de b&uacute;squeda</span>
			{/if}               
			<span class="d-block text-decoration-none fw-semibold py-2 px-3 rounded hover:main-bg hover:main-color active:main-bg active:main-color my-2 mp_new" role="button" onclick="mensaje.nuevo('','','',''); return false;">Escribir Nuevo Mensaje</span>
			<div class="divider"></div>
			<a class="d-block text-decoration-none fw-semibold py-2 px-3 rounded hover:main-bg hover:main-color active:main-bg active:main-color my-2 mp_avisos{if $tsAction == 'avisos'} main-bg main-color{/if}" href="{$tsConfig.url}/mensajes/avisos/">Avisos/Alertas</a>
		</div>
	</div>
</section>