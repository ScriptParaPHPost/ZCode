{foreach $tsForos key=i item=foro}
	<div class="foro border rounded mb-3 shadow">
		<div class="foro-header d-flex justify-content-start align-items-center column-gap-3 p-2 border-bottom">
			<img src="{$foro.super_img}" alt="{$foro.super_nombre}" class="avatar avatar-2">
			<h4 class="m-0 fw-normal">{$foro.super_nombre}</h4>
		</div>
		{foreach $foro.super_subcategorias item=cat}
			<div class="subforo p-3 border-bottom d-grid column-gap-2" style="grid-template-columns: 1fr 20% 35%;">
				<div class="d-flex justify-content-start align-items-center">
					<a href="{$tsConfig.url}/posts/{$cat.c_seo}/" class="text-decoration-none fw-semibold fs-5">{$cat.c_nombre}</a>
				</div>
				<div class="forum-stats d-flex justify-content-center align-items-end flex-column">
			   
			   	<div class="mb-1 d-flex justify-content-end align-items-center column-gap-2">{$cat.super_stats.comments|human} <span>Comentarios</span> {uicon name="speech_bubble"}</div>
			   	<div class="mb-0 d-flex justify-content-end align-items-center column-gap-2">{$cat.super_stats.posts|human} <span>Posts</span> {uicon name="document_stack"}</div>
			   	
			   </div>
				<div class="px-3">
					{if !empty($cat.ultimo.post_title)}
						<small class="d-block fw-bolder">Último post</small>
						<a href="{$cat.ultimo.post_url}" class="text-decoration-none fw-semibold text-truncate">{$cat.ultimo.post_title}</a>
						<small class="d-flex justify-content-between align-items-center">
							<span>por <a href="{$tsConfig.url}/perfil/{$cat.ultimo.user_name}" class="text-decoration-none">{$cat.ultimo.user_name}</a></span>
							<time>{$cat.ultimo.post_date|hace:true}</time>
						</small>
					{else}
						<div class="empty">No hay posts</div>
					{/if}
				</div>
			</div>
		{/foreach}
	</div>
{/foreach}