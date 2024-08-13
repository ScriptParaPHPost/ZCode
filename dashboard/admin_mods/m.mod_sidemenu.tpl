<h4>Principal</h4>
<ul class="cat-list">
	<li id="a_main"><a class="nav-item{if $tsAction == ''} active{/if}" href="{$tsConfig.url}/moderacion/">Centro de Moderaci&oacute;n</a></li>
</ul>

<h4>Denuncias</h4>
<ul class="cat-list">
	<li id="a_posts"><a class="nav-item d-flex justify-content-between align-items-center{if $tsAction == 'posts'} active{/if}" href="{$tsConfig.url}/moderacion/posts">Posts <span class="fw-semibold rounded shadow cadGe cadGe_{if $tsNovemods.repposts > 15}red{elseif $tsNovemods.repposts > 5}purple{else}green{/if}">{$tsNovemods.repposts}</span></a></li>
	<li id="a_fotos"><a class="nav-item d-flex justify-content-between align-items-center{if $tsAction == 'fotos'} active{/if}" href="{$tsConfig.url}/moderacion/fotos">Fotos <span class="fw-semibold rounded shadow cadGe cadGe_{if $tsNovemods.repfotos > 15}red{elseif $tsNovemods.repfotos > 5}purple{else}green{/if}">{$tsNovemods.repfotos}</span></a></li>
	<li id="a_mps"><a class="nav-item d-flex justify-content-between align-items-center{if $tsAction == 'mps'} active{/if}" href="{$tsConfig.url}/moderacion/mps">Mensajes  <span class="fw-semibold rounded shadow cadGe cadGe_{if $tsNovemods.repmps > 15}red{elseif $tsNovemods.repmps > 5}purple{else}green{/if}">{$tsNovemods.repmps}</span></a></li>
	<li id="a_users"><a class="nav-item d-flex justify-content-between align-items-center{if $tsAction == 'users'} active{/if}" href="{$tsConfig.url}/moderacion/users">Usuarios <span class="fw-semibold rounded shadow cadGe cadGe_{if $tsNovemods.repusers > 15}red{elseif $tsNovemods.repusers > 5}purple{else}green{/if}">{$tsNovemods.repusers}</span></a></li>
</ul>


{if $tsUser->is_admod || $tsUser->permisos.movub || $tsUser->permisos.moub}
	<h4>Gesti&oacute;n</h4>
	<ul class="cat-list">
		{if $tsUser->is_admod || $tsUser->permisos.movub}
			<li id="a_banusers"><a class="nav-item d-flex justify-content-between align-items-center{if $tsAction == 'banusers'} active{/if}" href="{$tsConfig.url}/moderacion/banusers">Usuarios suspendidos <span class="fw-semibold rounded shadow cadGe cadGe_{if $tsNovemods.supusers > 15}red{elseif $tsNovemods.suspusers > 5}purple{else}green{/if}">{$tsNovemods.suspusers}</span></a></li>
		{/if}
		{if $tsUser->is_admod || $tsUser->permisos.moub}
			<li id="a_buscador"><a class="nav-item{if $tsAction == 'posts'} active{/if}" href="{$tsConfig.url}/moderacion/buscador">Buscador de Contenido</a></li>
		{/if}
	</ul>
{/if}
{if $tsUser->is_admod || $tsUser->permisos.morp || $tsUser->permisos.morf}
	<h4>Papelera de Reciclaje</h4>
	<ul class="cat-list">
		{if $tsUser->is_admod || $tsUser->permisos.morp}
			<li id="a_pospelera"><a class="nav-item d-flex justify-content-between align-items-center{if $tsAction == 'pospelera'} active{/if}" href="{$tsConfig.url}/moderacion/pospelera">Post eliminados <span class="fw-semibold rounded shadow cadGe cadGe_{if $tsNovemods.pospelera > 15}red{elseif $tsNovemods.pospelera > 5}purple{else}green{/if}">{$tsNovemods.pospelera}</span></a></li>
		{/if}
		{if $tsUser->is_admod || $tsUser->permisos.morf}
			<li id="a_fopelera"><a class="nav-item d-flex justify-content-between align-items-center{if $tsAction == 'fopelera'} active{/if}" href="{$tsConfig.url}/moderacion/fopelera">Fotos eliminadas <span class="fw-semibold rounded shadow cadGe cadGe_{if $tsNovemods.fospelera > 15}red{elseif $tsNovemods.fospelera > 5}purple{else}green{/if}">{$tsNovemods.fospelera}</span></a></li>
		{/if}
	</ul>
{/if}
{if $tsUser->is_admod || $tsUser->permisos.mocp || $tsUser->permisos.mocc}
	<h4>Contenido desaprobado</h4>
	<ul class="cat-list">
		{if $tsUser->is_admod || $tsUser->permisos.mocp}
			<li id="a_revposts"><a class="nav-item d-flex justify-content-between align-items-center{if $tsAction == 'revposts'} active{/if}" href="{$tsConfig.url}/moderacion/revposts">Posts <span class="fw-semibold rounded shadow cadGe cadGe_{if $tsNovemods.revposts > 15}red{elseif $tsNovemods.revposts > 5}purple{else}green{/if}">{$tsNovemods.revposts}</span></a></li>
		{/if}
		{if $tsUser->is_admod || $tsUser->permisos.mocc}
			<li id="a_revcomentarios"><a class="nav-item d-flex justify-content-between align-items-center{if $tsAction == 'revcomentarios'} active{/if}" href="{$tsConfig.url}/moderacion/revcomentarios">Comentarios <span class="fw-semibold rounded shadow cadGe cadGe_{if $tsNovemods.revcomentarios > 15}red{elseif $tsNovemods.revcomentarios > 5}purple{else}green{/if}">{$tsNovemods.revcomentarios}</span></a></li>
		{/if}
	</ul>
{/if}