<div class="boxy-title">
   <h3>Gestionar foro</h3>
</div>
<div id="res" class="boxy-content">
	{if $tsSave}<div class="empty empty-success">Configuración guardada</div>{/if}
	{if empty($tsAct)}
		<div class="d-flex justify-content-between align-items-center mb-2">
			<h4>Lista de categorías</h4>
			<a href="{$tsConfig.url}/admin/foro/?act=nueva" class="btn btn-sm">Nueva</a>
		</div>
		{foreach $tsForos item=foro}
			<div class="super p-2 position-relative rounded border mb-3 d-grid column-gap-2" id="few_{$foro.fid}" style="grid-template-columns: 5rem 1fr;">
				<div class="super-icono d-flex justify-content-center align-items-start">
					<img src="{$foro.super_img}" class=" avatar avatar-6 rounded">
				</div>
				<div class="super-data">
					<strong class="d-block py-1 fs-4" style="color:{$foro.super_color}">{$foro.super_nombre}</strong>
					<span class="text-truncate truncate-1 py-1 my-1">{$foro.super_descripcion}</span>
					<div>
						{foreach $foro.super_subcategorias item=cat}
							<a class="text-decoration-none fw-semibold small badge main-color main-bg" href="{$tsConfig.url}/posts/{$cat.c_seo}">{$cat.c_nombre}</a>
						{/foreach}
					   {if $foro.remaining_tags}
	        				<span class="text-decoration-none fw-semibold small badge main-bg-color">{$foro.remaining_tags}</span>
	        			{/if}
					</div>
				</div>
				<div class="position-absolute p-3" style="top:-.125rem;right: .5rem;">
					<a href="{$tsConfig.url}/admin/foro/?act=editar&fid={$foro.fid}" class="btn btn-sm">Editar</a>
					<span role="button" onclick="foro.eliminar({$foro.fid}, true)" class="btn btn-sm btn-outline-danger">Borrar</span>

				</div>
			</div>
		{/foreach}
	{elseif $tsAct === 'editar' || $tsAct === 'nueva'}
	<script>
	document.addEventListener("DOMContentLoaded", function() {
		// Se usa jquery despues, porque se ejecuta despues de cargar
	   $('#super_img').on('change', () => {
	      var icono = $("#super_img option:selected").val();
	      $('#c_icon').css({
	         "background": 'url(\'{$tsConfig.assets}/images/categorias/'+icono+'\') no-repeat center',
	         "background-size": '32px'
	      })
	   })
	});
	</script>
		<h4>{if $tsAct == 'editar'}Editar{else}Crear{/if} categoría</h4>
		<form action="" method="post" autocomplete="off">
	      <fieldset>
	         <dl>
	            <dt><label for="ai_super_nombre">Nombre de la categoría:</label></dt>
	            <dd><input type="text" id="ai_super_nombre" name="super_nombre" maxlength="24" value="{$tsForo.super_nombre}" /></dd>
	         </dl>
	         <dl>
	            <dt><label for="ai_super_descripcion">Descripci&oacute;n de la categoría:</label></dt>
	            <dd><textarea id="ai_super_descripcion" name="super_descripcion">{$tsForo.super_descripcion}</textarea></dd>
	         </dl>
	         <dl>
	            <dt><label for="ai_super_color">Color de la categoría:</label></dt>
	            <dd><input type="color" id="ai_super_color" name="super_color" value="{$tsForo.super_color}" /></dd>
	         </dl>
				<dl>
					<dt><label for="super_img">Icono de la categor&iacute;a:</label></dt>
					<dd>
						<div class="d-flex justify-content-start align-items-center column-gap-2">
	                  <div style="background:url({$tsConfig.assets}/images/categorias/{if empty($tsForo.super_img)}1f30d.svg{else}{$tsForo.super_img}{/if}) no-repeat left center;" width="48" height="48" id="c_icon" class="d-block avatar avatar-3"></div>
						  	<select name="super_img" id="super_img" style="width:164px">
						  		{foreach from=$tsIcons key=i item=img}
									<option value="{$img}"{if $tsForo.super_img == $img} selected{/if}>{$img}</option>
						  		{/foreach}
						  	</select>
					  	</div>
					</dd>
				</dl>
	         <p><input type="submit" name="save" value="{if $tsAct == 'editar'}Guardar cambios{else}Crear Categor&iacute;a{/if}" class="btn btn-sm" ></p>
	      </fieldset>
	   </form>
	{/if}
</div>