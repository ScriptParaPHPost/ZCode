<div class="col">
	<section class="up-card">
		<div class="up-card--header" icon="true">
			<div class="up-header--icon">{uicon name="coins"}</div>
			<div class="up-header--title">
				<span>Top usuario con m&aacute;s puntos</span>
			</div>
		</div>
		<div class="up-card--body">
			{foreach from=$tsTops.puntos item=u}
				{include "m.top_usuarios-items.tpl"}
			{foreachelse}
				<div class="empty">Nada por aqui</div>
			{/foreach}
		</div>
	</section>
</div>

<div class="col">
	<section class="up-card">
		<div class="up-card--header" icon="true">
			<div class="up-header--icon">{uicon name="users"}</div>
			<div class="up-header--title">
				<span>Top usuario con m&aacute;s seguidores</span>
			</div>
		</div>
		<div class="up-card--body">
			{foreach from=$tsTops.seguidores item=u}
				{include "m.top_usuarios-items.tpl"}
			{foreachelse}
				<div class="empty">Nada por aqui</div>
			{/foreach}
		</div>
	</section>
</div>

<div class="col">
	<section class="up-card">
		<div class="up-card--header" icon="true">
			<div class="up-header--icon">{uicon name="medal"}</div>
			<div class="up-header--title">
				<span>Top usuario con m&aacute;s medallas</span>
			</div>
		</div>
		<div class="up-card--body">
			{foreach from=$tsTops.medallas item=u}
				{include "m.top_usuarios-items.tpl"}
			{foreachelse}
				<div class="empty">Nada por aqui</div>
			{/foreach}
		</div>
	</section>
</div>