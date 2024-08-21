1:
<div class="py-3 border-bottom d-grid column-gap-2" style="grid-template-columns:3rem 1fr;">
	<a href="{$tsConfig.url}/perfil/{$tsUser->nick}" class="d-block avatar avatar-5 overflow-hidden autor-image">
		<img loading="lazy" src="{$tsUser->use_avatar}" class="w-100 h-100 object-fit-cover" />
	</a>
	<div class="mensaje">
		<div class="d-flex justify-content-between align-items-center">
			<span>
				<a href="{$tsConfig.url}/perfil/{$tsUser->nick}" class="text-decoration-none fw-semibold autor-name">{$tsUser->nick|verificado}</a> 
				{if $tsUser->is_admod}<a href="{$tsConfig.url}/moderacion/buscador/1/1/{$mp.mp_ip}">{$mp.mp_ip}</a>{/if} 
			</span>
			<time class="mp-date small fst-italic">{$mp.mp_date|hace:true}</time>
		</div>
		<span class="d-block">{$mp.mp_body|nl2br}</span>
	</div>
</div>