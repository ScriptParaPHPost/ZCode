<div class="boxy-title">
   <h3>Generar favicon</h3>
</div>
<div id="res" class="boxy-content">
	{if $tsSave}<div class="empty empty-success">{$tsStatus}</div>{/if}
	<div class="mb-3">
		<small>Para generar los tamaños de 16, 32, 64, 128, 512, la imagen deberá ser de 1024x1024 o más, no menos ya que las imagenes se veran pixeladas al ser una resolución menor a la mencionada.</small>
		<form id="uploadForm" class="p-2 upform-group d-flex justify-content-start align-items-center column-gap-3" enctype="multipart/form-data">
			<div class="flex-grow-1">
        		<input type="file" class="upform-file" name="favicon" id="favicon" accept="image/*" required>
        	</div>
        	<button type="submit" class="btn">Subir Imagen</button>
    	</form>
	</div>
	<h4>Favicon existentes</h4>
	<table class="admin_table mt-3">
		<thead>
			<th>Imagen</th>
			<th>Nombre</th>
			<th>Tamaño</th>
			<th>Peso</th>
			<th>Extensión</th>
		</thead>
		<tbody>
			{foreach $tsAllFavicons key=f item=favicon}
				<tr>
					<td class="text-center"><img src="{$favicon.link}?{$smarty.now}" class="img-favicon rounded object-fit-cover" loading="lazy" alt="{$favicon.name}" style="width:{$favicon.px};height:{$favicon.px}"></td>
					<td>{$favicon.name}</td>
					<td>{$favicon.size}x{$favicon.size}</td>
					<td>{$favicon.weight}</td>
					<td>{$favicon.ext}</td>
				</tr>
			{/foreach}
		</tbody>
	</table>
</div>