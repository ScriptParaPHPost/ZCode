<form action="" method="GET" name="buscador" class="up-searcher">

	<div class="up-searcher--tabs">
		<label class="tab-item position-relative" data-tipo="web">
			<input type="radio" class="position-absolute" name="engine" value="web"{if !$tsEngine || $tsEngine == 'web'} checked{/if}>
			<span>{$tsConfig.titulo}</span>
		</label>
		<label class="tab-item position-relative" data-tipo="tags">
			<input type="radio" class="position-absolute" name="engine" value="tags"{if $tsEngine == 'tags'} checked{/if}>
			<span>Tags</span>
		</label>
		<label class="tab-item position-relative" data-tipo="google">
			<input type="radio" class="position-absolute" name="engine" value="google"{if $tsEngine == 'google'} checked{/if}>
			<span>Google</span>
		</label>
	</div>

	<input type="search" placeholder="Buscar en Web!" name="query" value="{$tsQuery}"/>
	<input type="text" placeholder="Usuario" name="autor" value="{$tsAutor}"/>

	<select name="category">
     	<option value="-1">Todas</option>
      {foreach from=$tsConfig.categorias item=c}
         <option value="{$c.cid}"{if $c.cid == $tsCategory} selected{/if}>{$c.c_nombre}</option>
      {/foreach}
   </select>

   <button type="submit" title="Buscar" class="d-flex justify-content-center align-items-center gap-2">
   	{uicon name="search"}
   	<span>Buscar...</span>
   </button>

</form>
<script>
	const googleSearch = '{$tsConfig.ads_search}';
	const tipoBuscador = '{if !$tsEngine}web{$tsEngine}{/if}';
</script>