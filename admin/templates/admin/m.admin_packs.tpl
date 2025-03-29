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
			   <img width="140" height="140" src="{$tsRoutes.assets.images}/category.svg" alt="Categor&iacute;as">
			   <strong style="margin-top:4px;display: block;">Categor&iacute;as</strong>
		   </a>
		   <a href="{$tsConfig.url}/admin/packs?act=abrir&path=medallas" class="block text-center">
			   <img width="140" height="140" src="{$tsRoutes.assets.images}/award.svg" alt="Medallas">
			   <strong style="margin-top:4px;display: block;">Medallas</strong>
		   </a>
		   <a href="{$tsConfig.url}/admin/packs?act=abrir&path=rangos" class="block text-center">
			   <img width="140" height="140" src="{$tsRoutes.assets.images}/ran.svg" alt="Rangos">
			   <strong style="margin-top:4px;display: block;">Rangos</strong>
		   </a>
	   </div>
   {elseif $tsAct === 'abrir'}
   	<div class="row">
		   {foreach $tsPack item=ic}
   		<div class="col-3">
   			<div class="p-1 border rounded shadow-sm mb-2">
	   			<div class="d-flex justify-content-start align-items-center column-gap-2">
	   				<div class="text-center">
	   					<img src="{$ic.url}" alt="{$ic.icon}" width="42" height="42">
	   				</div>
	   				<div class="flex-grow-1 position-relative">
			   			<span class="d-block fw-bold">{$ic.icon}</span>
			   			<small>{$ic.type}</small>
			   			<span class="position-absolute top-0 end-0" role="button" onclick="packs.borrar('{$tsDir}', '{$ic.hash}')" title="Eliminar">{uicon name="trash"}</span>
		   			</div>
	   			</div>
	   		</div>
   		</div>
		   {/foreach}
	   </div>
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
			  <p><span role="button" onclick="packs.subir('{$tsDir}')" class="btn">Agregar</span></p>
	   </form>

   {/if}
</div>