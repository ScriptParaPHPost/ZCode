<section class="up-card" category="topsPost">
	{include "CardHeader.tpl" iconName="trophy" link="top/posts/" title="Ver más" label="TOPs posts"}
	<div class="up-card--body">
		<div class="filter fw-semibold d-flex my-1">
			{foreach [
				'ayer' => ['active' => 'false', 'text' => 'Ayer'],
				'semana' => ['active' => 'false', 'text' => 'Semana'],
				'mes' => ['active' => 'false', 'text' => 'Mes'],
				'historico' => ['active' => 'true', 'text' => 'Hist&oacute;rico']
			] key=n item=arr}
				{include "FilterCardBox.tpl" active=$arr.active category="topsPosts" box="posts" period=$n label=$arr.text}
			{/foreach}
		</div>
		<div class="filterShow">
			{include "t.php_files/p.tops.posts.tpl"}
		</div>
	</div>
</section>