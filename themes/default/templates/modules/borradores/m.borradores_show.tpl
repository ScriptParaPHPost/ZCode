<h2>{$borrador.b_title}</h2>
<div class="py-2">
	<small>{$borrador.b_date}</small>
</div>
<p class="d-block">{$borrador.b_body}</p>
<div class="tags d-flex justify-content-start align-items-center gap-3">
	{foreach $borrador.b_tags item=tag}
		<span class="badge main-bg">#{$tag}</span>
	{/foreach}
</div>