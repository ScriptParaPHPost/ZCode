<div id="comment_pub--{$p.pub_id}" class="Story_Comments position-relative"{if $p.p_comments == 0 && $p.p_likes == 0} style="display:none"{/if}>
	<div class="position-xl-absolute position-relative people-likes" style="{if $p.p_likes == 0} display:none;{/if}">
		<div class="likes text-center small up-badge">
			<span class="floatL" id="like_text--{$p.pub_id}">{$p.likes.text}</span>
		</div>
	</div>
	<div class="commentLists">
		<div id="list_comments--{$p.pub_id}" class="commentList">
			{if $p.p_comments > 2 && !$p.hide_more_cm}
				<div class="ufiItem">
					 <div class="more_comments translucent-bg py-1 d-flex justify-content-center align-items-center small">
						  <span role="button" onclick="muro.more_comments({$p.pub_id}, this); return false">Ver los {$p.p_comments} comentarios</span>
						  <img src="{$tsConfig.assets}/images/spinner.gif" style="display: none;"/>
					 </div>
				</div>
			{/if}
			{foreach from=$p.comments item=c}
				<div class="ufiItem translucent-bg mb-2 rounded p-1" id="cmt_{$c.cid}">
					<div class="d-grid column-gap-2" style="grid-template-columns: 2.5rem 1fr;place-items:center start;">
						<a href="{$tsConfig.url}/perfil/{$c.user_name}" class="avatar avatar-4 d-block">
							<img loading="lazy" alt="{$c.user_name}" src="{$c.avatar}" class="w-100 h-100 object-fit-cover"/>
						</a>
						<div class="mensaje">
							{if $p.p_user == $tsUser->uid || $c.c_user == $tsUser->uid  || $tsUser->is_admod || $tsUser->permisos.moecm}
								<span class="close"><a href="#" onclick="muro.del_pub({$c.cid}, 2); return false" class="uiClose" title="Eliminar"></a></span>
							{/if}
							<a href="{$tsConfig.url}/perfil/{$c.user_name}" class="fw-semibold text-decoration-none">{$c.user_nam|verificadoe}</a>
							<span>&raquo; {$c.c_body|quot}</span>
							<div class="cmInfo small">
								{$c.c_date|fecha} &middot; 
								<span role="button" onclick="muro.like_this({$c.cid}, 'com', this); return false;" class="fw-semibold">{$c.like}</span> 
								<span class="cm_like"{if $c.c_likes == 0} style="display:none"{/if}>&middot; <i></i> 
									<span role="button" onclick="muro.show_likes({$c.cid}, 'com'); return false;" id="like_comment--{$c.cid}" class="fw-semibold">{$c.c_likes} persona{if $c.c_likes > 1}s{/if}</span>
								</span>
								{if $tsUser->is_admod} &middot; <span class="cmInfo fw-bold">{$c.c_ip}</span>{/if}</div>
						</div>
					</div>
				</div>
			{/foreach}
		</div> 
	</div>
	{if $tsPrivacidad.mf.v == true && $tsUser->is_member || $tsType == 'news'}
		<div class="newComment">
			<input type="hidden" name="pid" value="{$p.pub_id}">
			<div class="formulario position-relative">
				<img loading="lazy" src="{$tsUser->use_avatar}" class="position-absolute rounded-circle" class="object-fit-cover"/>
				<textarea class="comentar overflow-hidden rounded-pill w-100" placeholder="Escribe un comentario..." id="comment--{$p.pub_id}" name="add_wall_comment"></textarea>
			</div>
		</div>
	{/if}
</div>