{foreach [
	'puntos' => ['icon' => 'coins', 'text' => 'puntos', 'data' => 'post_puntos'],
	'favoritos' => ['icon' => 'bookmark', 'text' => 'favoritos', 'data' => 'post_favoritos'],
	'comments' => ['icon' => 'thread', 'text' => 'comentado', 'data' => 'post_comments'],
	'seguidores' => ['icon' => 'user-add', 'text' => 'seguidores', 'data' => 'post_seguidores']
] key=k item=arr}
	<div class="col">
		<section class="up-card">
			{include "CardHeader.tpl" iconName=$arr.icon label="Top post con m&aacute;s {$arr.text}"}
			<div class="up-card--body">
				{foreach from=$tsTops[$k] item=p}
					{include "m.top_posts-items.tpl" dato=$p[$arr.data]}
				{foreachelse}
					<div class="empty">Nada por aqui</div>
				{/foreach}
			</div>
		</section>
	</div>
{/foreach}