{include "main_header.tpl"}

	{include "m.perfil_headinfo.tpl"}
	
	<div class="perfil-main d-block d-lg-grid gap-3 {$tsGeneral.stats.user_rango.1}">
		<div class="perfil-content general p-3">
			<div id="info" pid="{$tsInfo.uid}"></div>
			<div id="perfil_content">
				{if $tsPrivacidad.m.v == false}
					<div id="perfil_wall" status="activo" class="widget">
					 	<div class="empty">{$tsPrivacidad.m.m}</div>
					 	<script type="text/javascript">
							perfil.load_tab('info', $('#informacion'));
					 	</script>
					</div>
				{elseif $tsType == 'story' || $tsType == 'news'}
					{include "m.perfil_$tsType.tpl"}
				{else}
					{include "m.perfil_muro.tpl"}
				{/if}
			</div>
			<div style="width:100%;text-align:center;display:none" id="perfil_load">
				<img src="{$tsConfig.assets}/images/loading_bar.gif" loading="lazy" />
			</div>
		</div>
		<div class="perfil-sidebar">{include "m.perfil_sidebar.tpl"}</div>
	</div>
					 
{include "main_footer.tpl"}