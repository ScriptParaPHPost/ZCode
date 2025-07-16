{foreach $borradores key=i item=b}
	<div class="p-3 rounded shadow-sm position-relative post_estado estado_{$b.b_status} mb-3">
		<div class="header d-flex justify-content-start align-items-start gap-2">
			<img src="{$b.c_img}" alt="{$b.c_nombre}" loading="lazy" >
			<a class="fs-4 text-truncate text-decoration-none" href="{$tsConfig.url}/borradores/?borrador_id={$b.bid}">{$b.b_title}</a>
		</div>
		<div class="post">
			<small class="d-block position-absolute">{$b.c_nombre} | {$b.b_date}</small>
			<small class="fst-italic">{$b.b_causa}</small>
		</div>
	</div>
{/foreach}