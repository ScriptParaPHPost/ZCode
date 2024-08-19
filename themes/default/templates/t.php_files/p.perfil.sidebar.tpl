<div class="users orden-21 d-grid gap-2 place-center py-2">
	{foreach from=$tsGeneral.seguidores.data item=s}
	<a href="{$tsConfig.url}/perfil/{$s.user_name}" class="text-decoration-none user orden-item overflow-hidden d-flex justify-content-center align-items-center avatar avatar-3 rounded translucent-bg border">
		<img src="{$s.avatar}" class="w-100 h-100"/>
	</a>
	{/foreach}
</div>