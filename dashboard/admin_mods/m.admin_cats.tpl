{if $tsAct == '' || $tsAct == 'editar' || $tsAct == 'nueva'}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
   {if $tsAct == ''} 
   new Sortable(document.getElementById('cats_orden'), {
      animation: 150,
      dragClass: 'arrastrar',
      selectedClass: 'seleccionado',
      ghostClass: 'blue-background-class',
      fallbackTolerance: 3,
      multiDrag: true,
      store: {
         // Guardar orden
         set: sortable => $.post(global_data.url + `/admin-ordenar-categorias.php`, 'cats=' + sortable.toArray().join(','))
      }
   });
   {/if}
   $('#cat_img').on('change', () => {
      var icono = $("#cat_img option:selected").val();
      $('#c_icon').css({
         "background": 'url(\'{$tsConfig.assets}/images/categorias/'+icono+'\') no-repeat center',
         "background-size": '32px'
      })
   })
});
</script>
{/if}
<div class="boxy-title">
	<h3>Administrar Categor&iacute;as</h3>
</div>
<div id="res" class="boxy-content">
	{if $tsSave}<div class="empty empty-success">Tus cambios han sido guardados.</div>{/if}
	{if $tsAct == ''}
		{if !$tsSave}<div class="empty empty-danger">Puedes cambiar el orden de las categor&iacute;as tan s&oacute;lo con arrastrarlas con el puntero.</div>{/if}
		<table class="admin_table">
	      <h4>Categor&iacute;as</h4>
	      <thead>
	         <th>#</th>
	         <th>ID</th>
	         <th>Categoría</th>
	       	<th>Acción</th>
	      </thead>
			<tbody id="cats_orden">
				{foreach from=$tsConfig.categorias item=c}
				<tr id="{$c.cid}" data-id="{$c.cid}">
					<td style="width:45px;" class="handle text-center fw-semibold">{$c.c_orden}</td>
					<td style="width:45px;" class="text-center fw-semibold">{$c.cid}</td>
					<td style="text-align:left;">
						<div class="d-grid" style="grid-template-columns: 3rem 1fr;">
							<img src="{$c.c_img}" alt="{$c.c_nombre} - {$c.c_seo}" class="avatar avatar-3">
							<div>
								<strong class="d-block">{$c.c_nombre}</strong>
								<small class="fst-italic">{$c.c_descripcion}</small>
							</div>
						</div>
					</td>
					<td class="admin_actions" style="width:100px;">
						<div class="d-flex justify-content-center align-items-center column-gap-2">
							<a href="{$tsConfig.url}/admin/cats?act=editar&cid={$c.cid}&t=cat" title="Editar Categor&iacute;a">{uicon name="pen"}</a>
							<a href="{$tsConfig.url}/admin/cats?act=borrar&cid={$c.cid}&t=cat" title="Borrar Categor&iacute;a">{uicon name="trash"}</a>
						</div>
					</td>
				</tr>
				{/foreach}
			</tbody>
			<tfoot>	
				<td colspan="3">&nbsp;</td>
			</tfoot>
		</table>
		<a href="{$tsConfig.url}/admin/cats?act=nueva&t=cat" class="btn btn-sm btnOk">Agregar Nueva Categor&iacute;a</a>
		<a href="{$tsConfig.url}/admin/cats?act=change" class="btn btn-sm">Mover Posts</a>							
	{elseif $tsAct == 'editar' || $tsAct == 'nueva'}
		<form action="" method="post" autocomplete="off">
		  	<fieldset>
				<legend style="text-transform: capitalize;">{$tsAct}</legend>
				{if $tsAct == 'nueva'}
					<div class="empty empty-warning">Si deseas m&aacute;s iconos para las categor&iacute;as debes subirlos al directorio: <code class="d-block">{$tsConfig.assets}/images/categorias/</code></div>
				{/if}
				<dl>
					<dt><label for="cat_name">Nombre de la categor&iacute;a:</label></dt>
					<dd><input type="text" id="cat_name" name="c_nombre" value="{$tsCat.c_nombre}" /></dd>
				</dl>
				<dl>
					<dt><label for="cat_descripcion">Descripción de la categor&iacute;a:</label></dt>
					<dd><textarea name="c_descripcion" id="cat_descripcion" rows="13">{$tsCat.c_descripcion}</textarea></dd>
				</dl>
				<dl>
					<dt><label for="cat_img">Icono de la categor&iacute;a:</label></dt>
					<dd>
						<div class="d-flex justify-content-start align-items-center column-gap-2">
	                  <div style="background:url({$tsConfig.assets}/images/categorias/{if empty($tsCat.c_img)}1f30d.svg{else}{$tsCat.c_img}{/if}) no-repeat left center;" width="48" height="48" id="c_icon" class="d-block avatar avatar-3"></div>
						  	<select name="c_img" id="cat_img" style="width:164px">
						  		{foreach from=$tsIcons key=i item=img}
									<option value="{$img}"{if $tsCat.c_img == $img} selected{/if}>{$img}</option>
						  		{/foreach}
						  	</select>
					  	</div>
					</dd>
				</dl>
				<p><input type="submit" name="save" value="{if $tsAct == 'editar'}Guardar cambios{else}Crear Categor&iacute;a{/if}" class="btn btn-sm"/  ></p>
		  	</fieldset>
		</form>
	{elseif $tsAct == 'borrar'}
		{if $tsError}<div class="empty empty-danger">{$tsError}</div>{/if}
		{if $tsType == 'cat'}
		  	<form action="" method="post" id="admin_form">
				<label for="h_mov" style="width:500px;">Borrar categor&iacute;a y mover las subcategor&iacute;as y demas datos a otra categor&iacute;a diferente. Mover datos a:</label>
				<select name="ncid">
					<option value="-1">Categor&iacute;as</option>
					{foreach from=$tsConfig.categorias item=c}
						{if $c.cid != $tsCID}
							<option value="{$c.cid}">{$c.c_nombre}</option>
						{/if}
					 {/foreach}
				</select>
			<hr />
			<label>&nbsp;</label> <input type="submit" name="save" value="Guardar cambios" class="mBtn btnOk">
		  </form>	                                        
		  {/if}
	{elseif $tsAct == 'change'}
		{if $tsError}<div class="empty empty-danger">{$tsError}</div>{/if}
		<form action="" method="post" id="admin_form">
			<label style="width:500px;">Mover todos los posts de la categor&iacute;a </label>
			<select name="oldcid">
				<option value="-1">Categor&iacute;as</option>
				{foreach from=$tsConfig.categorias item=c}
					{if $c.cid != $tsCID}
						<option value="{$c.cid}">{$c.c_nombre}</option>
					{/if}
				 {/foreach}
			</select>
			<label style="width:500px;"> a </label>
			<select name="newcid">
				<option value="-1">Categor&iacute;as</option>
				{foreach from=$tsConfig.categorias item=c}
					{if $c.cid != $tsCID}
						<option value="{$c.cid}">{$c.c_nombre}</option>
					{/if}
				 {/foreach}
			</select>
			<hr />
			<label>&nbsp;</label> <input type="submit" name="save" value="Guardar cambios" class="mBtn btnOk">
		</form>	                                        
	{/if}
</div>