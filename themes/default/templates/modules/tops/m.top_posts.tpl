<div class="col">
	<section class="up-card">
		<div class="up-card--header" icon="true">
			<div class="up-header--icon">{uicon name="coins"}</div>
			<div class="up-header--title">
				<span>Top post con m&aacute;s puntos</span>
			</div>
		</div>
		<div class="up-card--body">
			{foreach from=$tsTops.puntos item=p}
				{assign "dato" "{$p.post_puntos}"}
				{include "m.top_posts-items.tpl"}
			{foreachelse}
				<div class="empty">Nada por aqui</div>
			{/foreach}
		</div>
	</section>
</div>

<div class="col">
	<section class="up-card">
		<div class="up-card--header" icon="true">
			<div class="up-header--icon">{uicon name="bookmark"}</div>
			<div class="up-header--title">
				<span>Top post m&aacute;s favoritos</span>
			</div>
		</div>
		<div class="up-card--body">
			{foreach from=$tsTops.favoritos item=p}
				{assign "dato" "{$p.post_favoritos}"}
				{include "m.top_posts-items.tpl"}
			{foreachelse}
				<div class="empty">Nada por aqui</div>
			{/foreach}
		</div>
	</section>
</div>

<div class="col">
	<section class="up-card">
		<div class="up-card--header" icon="true">
			<div class="up-header--icon">{uicon name="thread"}</div>
			<div class="up-header--title">
				<span>Top post m&aacute;s comentado</span>
			</div>
		</div>
		<div class="up-card--body">
			{foreach from=$tsTops.comments item=p}
				{assign "dato" "{$p.post_comments}"}
				{include "m.top_posts-items.tpl"}
			{foreachelse}
				<div class="empty">Nada por aqui</div>
			{/foreach}
		</div>
	</section>
</div>

<div class="col">
	<section class="up-card">
		<div class="up-card--header" icon="true">
			<div class="up-header--icon">{uicon name="user-add"}</div>
			<div class="up-header--title">
				<span>Top post con m&aacute;s seguidores</span>
			</div>
		</div>
		<div class="up-card--body">
			{foreach from=$tsTops.seguidores item=p}
				{assign "dato" "{$p.post_seguidores}"}
				{include "m.top_posts-items.tpl"}
			{foreachelse}
				<div class="empty">Nada por aqui</div>
			{/foreach}
		</div>
	</section>
</div>