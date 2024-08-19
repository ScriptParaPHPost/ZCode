{include "main_header.tpl"}

	<div id="alerta_guarda"></div>

	<a name="alert-cuenta"></a>
	<div class="folder-tabs d-block d-xl-grid">
		<ul class="folder--menu d-xl-block d-flex justify-content-around align-items-center flex-wrap">
			{foreach $tsMenuCuenta item=item key=i}
				<a class="folder--item mb-2 mb-xl-0 rounded d-flex justify-content-start align-items-center text-decoration-none py-1 px-2 p-xl-3 fw-semibold position-relative gap-2 main-bg:rgb-hover{if $tsAccion == $i} main-bg:rgb active{/if}" title="{$item.name}" href="{$tsConfig.url}/cuenta/{$i}">{uicon class="box iconify-28" name="{$item.icon}"} <span class="d-none d-lg-block">{$item.name}</span></a>
			{/foreach}
		</ul>
		<form class="folder--form" method="post" action="" name="editarcuenta">
	      <input type="hidden" name="pagina" value="{$tsAccion}">
			{include "m.cuenta_$tsAccion.tpl"}
		</form>
		<div class="">
			<div class="mb-3">
				<h5 class="d-flex justify-content-between align-items-center">2FA: {if $tsG2FA === false}Desa{else}A{/if}ctivado{if $tsG2FA === false} <small id="countdown">30s</small>{/if}</h5>
				{if $tsG2FA === false}
					<div id="regenerate">
						{include "p.cuenta.regenerate.tpl"}
					</div>
				{else}
					<div class="text-center">
						{uicon name="lightning" size="4rem"}
					</div>
					<div class="row">
						<div class="col-12 col-lg-6">
							<span role="button" class="btn btn-sm d-block text-center remove_2fa">Desactivar 2FA</span>
						</div>
						<div class="col-12 col-lg-6">
							<span role="button" class="btn btn-sm d-block text-center regenerate_token">Generar Token</span>
						</div>
					</div>
					
				{/if}
			</div>

			{foreach $SocialMager key=nombre item=social}
			   <div class="btn-group-socials d-block">
			      {if $tsPerfil.socials.$nombre}
			         <a class="btn btn--{$nombre}" href="javascript:desvincular('{$nombre}')">
			            {uicon name="$nombre" folder="prime" class="btn--icon"}
			            <span class="btn--text">Desvincular {$nombre}</span>
			         </a>
			      {else}
			         <a class="btn btn--{$nombre}" href="{$social}">
			            {uicon name="$nombre" folder="prime" class="btn--icon"}
			         	<span class="btn--text">Vincular {$nombre}</span>
			      	</a>
			      {/if}
			   </div>
			{foreachelse}
				<div class="empty">Conexiones a tus redes sociales!</div>
			{/foreach}
		</div>
	</div>
	

{include "main_footer.tpl"}