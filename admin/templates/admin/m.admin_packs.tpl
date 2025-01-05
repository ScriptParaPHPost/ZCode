<div class="boxy-title">
   <h3>Control de paquetes de imagenes</h3>
</div>
<div id="res" class="boxy-content">
	<p class="alerts ok">Desde aqu&iacute; podrás ver, agregar, eliminar las imagenes e iconos que estan guardados en "<a href="{$tsConfig.url}/admin/packs?act=abrir&path=rangos">rangos</a>", "<a href="{$tsConfig.url}/admin/packs?act=abrir&path=medallas">medallas</a>", "<a href="{$tsConfig.url}/admin/packs?act=abrir&path=categorias">categorias</a>".</p>
   {if $tsSave}<div class="alerts ok">Tus cambios han sido guardados.</div>{/if}
   <hr class="separator">
   {if $tsAct === ''}
	   <div style="display:grid;gap:10px;grid-template-columns: repeat(3, 1fr);">
		   <a href="{$tsConfig.url}/admin/packs?act=abrir&path=categorias" class="block text-center">
			   <img width="140" height="140" src="{$tsConfig.assets}/images/category.svg" alt="Categor&iacute;as">
			   <strong style="margin-top:4px;display: block;">Categor&iacute;as</strong>
		   </a>
		   <a href="{$tsConfig.url}/admin/packs?act=abrir&path=medallas" class="block text-center">
			   <img width="140" height="140" src="{$tsConfig.assets}/images/award.svg" alt="Medallas">
			   <strong style="margin-top:4px;display: block;">Medallas</strong>
		   </a>
		   <a href="{$tsConfig.url}/admin/packs?act=abrir&path=rangos" class="block text-center">
			   <img width="140" height="140" src="{$tsConfig.assets}/images/ran.svg" alt="Rangos">
			   <strong style="margin-top:4px;display: block;">Rangos</strong>
		   </a>
	   </div>
   {elseif $tsAct === 'abrir'}
	   <table class="admin_table">
		   <thead>
			   <tr>
				   <th>Icono</th>
				   <th>Nombre</th>
				   <th>Tipo</th>
				   <th>Acciones</th>
			   </tr>
		   </thead>
		   {foreach $tsPack item=ic}
			   <tr class="{$ic.hash}">
				   <td style="text-align:center;"><img src="{$ic.url}" alt="{$ic.icon}" width="32" height="32"></td>
				   <td style="width: max-content">{$ic.icon}</td>
				   <td>{$ic.type}</td>
				   <td class="admin_actions text-center align-middle">
				  		<span role="button" onclick="packs.borrar('{$tsDir}', '{$ic.hash}')" title="Eliminar">{uicon name="trash"}</span>
			   	</td>
			   </tr>
		   {/foreach}
	   </table>
	   <hr class="separator">
	   <div style="text-align:center;">
		   <a href="{$tsConfig.url}/admin/packs?act=agregar&path={$tsDir}" class="btn">Agregar icono en {$tsDir}</a>
	   </div>
	  {elseif $tsAct === 'agregar'}
		  	<form method="post" enctype="multipart/form-data">
			  	<input type="hidden" name="path" id="path" value="{$tsDir}">
		  		<div class="form-line">
					<label for="image" class="d-block fw-bold">Sube una imagen...</label>
					<div class="p-3">
						<input type="file" class="form-control-file" name="image" id="image">
					</div>
		  		</div>
			  <p><span role="button" onclick="packs.subir()" class="btn">Agregar</span></p>
	   </form>

   {/if}
</div>