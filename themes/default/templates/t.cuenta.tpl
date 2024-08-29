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
			<h4>Tus redes sociales</h4>
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
				<div class="empty">Conexiones a tus redes sociales, pero aun {$tsConfig.titulo} no las ha configurado!</div>
			{/foreach}
		</div>
	</div>
	

{include "main_footer.tpl"}