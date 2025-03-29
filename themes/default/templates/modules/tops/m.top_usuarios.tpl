<div class="col">
	<section class="up-card">
		{include "CardHeader.tpl" iconName="coins" label="Top usuario con m&aacute;s puntos"}
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
		{include "CardHeader.tpl" iconName="users" label="Top usuario con m&aacute;s seguidores"}
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
		{include "CardHeader.tpl" iconName="medal" label="Top usuario con m&aacute;s medallas"}
		<div class="up-card--body">
			{foreach from=$tsTops.medallas item=u}
				{include "m.top_usuarios-items.tpl"}
			{foreachelse}
				<div class="empty">Nada por aqui</div>
			{/foreach}
		</div>
	</section>
</div>