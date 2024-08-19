{include "main_header.tpl"}

{if $tsAction == ''}
	<div class="row">
		<div class="col-12 col-lg-12 col-xl-8">
			{include "m.fotos_home_content.tpl"}
		</div>
		<div class="col-12 col-lg-12 col-xl-4">
			{include "m.fotos_home_sidebar.tpl"}
		</div>
	</div>
	
{elseif $tsAction == 'agregar' || $tsAction == 'editar'}
	{include "m.fotos_add_form.tpl"}
	{include "m.fotos_add_sidebar.tpl"}

{elseif $tsAction == 'ver'}
	<div class="row">
		<div class="col-12 col-lg-12 col-xl-2">
			{include "m.fotos_ver_left.tpl"}
		</div>
		<div class="col-12 col-lg-12 col-xl-7">
			{include "m.fotos_ver_content.tpl"}
		</div>
		<div class="col-12 col-lg-12 col-xl-3">
			{include "m.fotos_ver_right.tpl"}
		</div>
	</div>

{elseif $tsAction == 'album'}
	{include "m.fotos_album.tpl"}

{elseif $tsAction == 'favoritas'}
	<div class="emptyData">En construcci&oacute;n</div>
	
{/if}

{include "main_footer.tpl"}