<div class="row w-100">
	<div class="col-sm-4 col-xl-12">
		<section class="lastPosts up-card">
			<div class="up-card--header" icon="true">
				<div class="up-header--icon">{uicon name="thread"}</div>
				<div class="up-header--title">
					<span>&Uacute;ltimos comentarioss</span>
				</div>
			</div>
			<div class="up-card--body">
				{foreach from=$tsLastComments item=c}
					<div class="p-2"><strong>{if $tsUser->is_admod && $tsConfig.c_see_mod == 1 && $tsFoto.f_status != 0 || $tsFoto.user_activo == 0}<span style="color: {if $c.user_activo == 0} brown {elseif $c.f_status == 1} purple {elseif $c.f_status == 2} red{/if};" class="qtip" title="{if $c.user_activo == 0}El autor del comentario tiene la cuenta desactivada {elseif $c.f_status == 1} La foto se encuentra oculta {elseif $c.f_status == 2} La foto se encuentra eliminada{/if}">{/if}{$tsUser->getUsername($c.c_user)}{if $c.user_activo == 0 || $c.f_status != 0 && $tsUser->is_admod}</span>{/if}</strong> &raquo; <a href="{$c.foto_url}">{$c.f_title}</a>
					</div>
		   	{foreachelse}
		     		<div class="empty">No hay comentarios</div>
				{/foreach}
			</div>
		</section>
	</div>
	<div class="col-sm-4 col-xl-12">
		<section class="up-card">
			<div class="up-card--header" icon="true">
				<div class="up-header--icon">
					{uicon name="graph-box"}
				</div>
				<div class="up-header--title">
					<span>Estad&iacute;sticas</span>
				</div>
			</div>
			<div class="up-card--body up-card--stats">
				<div class="d-grid gap-2">
					<div class="text-center text-uppercase small py-3 position-relative">
						{uicon name="graph-box" class="position-absolute z-1 iconify-62" size="5rem" stroke="var(--main-bg)"}
						<span class="z-2 fw-bold position-relative body-color">
							<span class="h3 d-block m-0 up-effect up-effect--decrypt" data-count="{$tsStats.stats_miembros}">0</span> Miembros
						</a>
					</div>
					<div class="text-center text-uppercase small py-3 position-relative">
						{uicon name="picture" class="position-absolute z-1 iconify-62" size="5rem" stroke="var(--main-bg)"}
						<span class="z-2 fw-bold position-relative body-color">
							<span class="h3 d-block m-0 up-effect up-effect--decrypt" data-count="{$tsStats.stats_fotos}">0</span> Fotos
						</a>
					</div>
					<div class="text-center text-uppercase small py-3 position-relative">
						{uicon name="thread" class="position-absolute z-1 iconify-62" size="5rem" stroke="var(--main-bg)"}
						<span class="z-2 fw-bold position-relative body-color">
							<span class="h3 d-block m-0 up-effect up-effect--decrypt" data-count="{$tsStats.stats_foto_comments}">0</span> Comentarios
						</span>
					</div>
				</div>
			</div>
		</section>
	</div>
	<div class="col-sm-4 col-xl-12">
		{include "m.global_ads_300.tpl"}
	</div>
</div>