<div id="portal_news" class="showHide" status="activo">
	<div id="info" pid="{$tsInfo.uid}"></div>
	<div id="perfil-form" class="widget">
		{include "perfil/m.perfil_muro_form.tpl"}
	</div>
	<div class="widget clearfix" id="perfil-news">
		<div id="news-content">
			{include "perfil/m.perfil_muro_story.tpl"}                         
		</div>
		<!-- more -->
		{if $tsMuro.total >= 10}
			<div class="more-pubs">
				<div class="content">
					<span role="button" onclick="muro.stream.loadMore('news'); return false;" class="a_blue">Publicaciones m&aacute;s antiguas</span>
				</div>
			</div>
		{elseif $tsMuro.total == 0}
			<div class="empty">Hola <u>{$tsUser->nick|verificado}</u>. &iquest;Por qu&eacute; no empiezas a seguir usuarios?</div>
		{/if}
	</div>
</div>