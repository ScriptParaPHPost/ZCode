<div class="item border rounded p-2 d-grid column-gap-2" id="div_cmnt_{$tsComment.comment_id}" style="grid-template-columns: 3rem 1fr;">
	<a href="{$tsConfig.url}/perfil/{$tsUser->nick}" class="avatar avatar-5 rounded overflow-hidden">
		<img loading="lazy" src="{$tsComment.comment_user}" class="w-100 h-100 object-fit-cover"/>
	</a>
	<div class="position-relative">
		{if $tsComment.comment_autor == $tsUser->uid}
			<span role="button" onclick="fotos.borrar({$tsComment.comment_id}, 'com'); return false" class="position-absolute" style="top: 0;right: 0;">{uicon name="trash-alt" class="pe-none"}</span>
		{/if}
		<div class="info">
			<a href="{$tsConfig.url}/fotos/{$tsUser->nick}" class="fw-semibold text-decoration-none">{$tsUser->nick|verificado}</a> - 
			<em>{$tsComment.comment_date|hace:true}</em>
		</div>
		<div class="clearfix">{$tsComment.comment|nl2br}</div>
	</div>
</div>