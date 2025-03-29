<section class="up-card" category="topsUser">
	{include "CardHeader.tpl" iconName="trophy" link="top/usuarios/" title="Ver más" label="TOPs usuarios"}
	<div class="up-card--body">
		<div class="filter fw-semibold d-flex my-1">
			{foreach [
				'ayer' => ['active' => 'false', 'text' => 'Ayer'],
				'semana' => ['active' => 'false', 'text' => 'Semana'],
				'mes' => ['active' => 'false', 'text' => 'Mes'],
				'historico' => ['active' => 'true', 'text' => 'Hist&oacute;rico']
			] key=n item=arr}
				{include "FilterCardBox.tpl" active=$arr.active category="topsUser" box="usuarios" period=$n label=$arr.text}
			{/foreach}
		</div>
		<div class="filterShow" itemscope itemtype="https://schema.org/ItemList">
			{include "t.php_files/p.tops.usuarios.tpl"}
		</div>
	</div>
</section>