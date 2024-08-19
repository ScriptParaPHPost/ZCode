<div id="search_box" class="new-search posts mb-3">

	<div class="search-body">
		<form action="{$tsConfig.url}/buscador/" name="search" gid="{$tsConfig.ads_search}">
				
			<div class="upform-group upform-search">
				<div class="upform-group-input">
					<input class="upform-input" type="search" name="query" id="search" placeholder="Buscar post..." autocomplete="OFF">
				</div>
				<button type="submit" aria-label="Buscar">{uicon name="search"}</button>
			</div>

			<input type="hidden" name="engine" value="web" />
      	<input type="hidden" name="category" value="" />
		</form>
	</div>

</div>