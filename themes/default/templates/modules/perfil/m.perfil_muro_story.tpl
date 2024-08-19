{foreach from=$tsMuro.data item=p}
	<div class="Story mb-3 d-block d-lg-grid column-gap-3 p-2 pb-3" id="pub_{$p.pub_id}" type="{$p.p_type}">
		<a href="{$tsConfig.url}/perfil/{$p.user_name}" class="Story_Pic d-block">
			<img alt="{$p.user_name}" class="image rounded" loading="lazy" src="{$p.avatar}"/>
		</a>
		<div class="Story_Content">
			<div class="Story_Head">
				{if $p.p_user == $tsUser->uid || $p.p_user_pub == $tsUser->uid || $tsUser->is_admod || $tsUser->permisos.moepm}
					<!-- Eliminamos la publicación -->
					<div class="Story_Hide">
						<span role="button" onclick="muro.del_pub({$p.pub_id},1); return false;" title="Eliminar la publicaci&oacute;n" class="uiClose"></span>
					</div>
				{/if}
				<div class="Story_Message">
					<div class="autor">
						<a href="{$tsConfig.url}/perfil/{$p.user_name}" class="fw-semibold text-decoration-none">{if $p.user_name == $tsUser->nick}{$tsUser->nick}{else}{$p.user_name}{/if}</a>
					</div>
					<span class="d-block my-2">{$p.p_body|quot}</span>
					{if $p.p_type != 1}
						<div class="mvm px-3 clearfix">
							{if $p.p_type == 2}
								<div class="muro-image" role="button" onclick="muro.load_atta('foto', '{$p.adj_url}', this)">
									<img loading="lazy" src="{$p.adj_image}" class="w-100 h-100 object-fit-cover pe-none" />
								</div>
							{elseif $p.p_type == 3}
								<div class="muro-link">
									<a href="{$p.adj_url}" target="_blank" class="muro-link--title">{$p.adj_title}</a>
									<span class="muro-link--description">{$p.adj_description}</span>
								</div>
							{elseif $p.p_type == 4}
								<div class="muro-video" onclick="muro.load_atta('video', '{$p.adj_url}', this)">
									<lite-youtube loading="lazy" videoid="{$p.adj_url}" style="background-image: url('https://i.ytimg.com/vi/{$p.adj_url}/maxresdefault.jpg');"></lite-youtube>
									<div class="muro-video--description">
										<span class="muro-link--title">{$p.adj_title}</span>
										<span class="muro-link--description">{$p.adj_description}</span>
									</div>
								</div>
							{/if}
						</div>
					{/if}
				</div><!-- .Story_Message -->
			</div><!-- .Story_Head -->
			<div class="Story_Foot">
				<div class="Story_Info d-flex justify-content-start align-items-center column-gap-2">
					{if $p.p_type == 1 && $p.p_user == $p.p_user_pub}
						{uicon name="laptop"}
					{elseif $p.p_type == 4}
						{uicon name="tv-mode"}
					{elseif $p.p_type == 3}
						{uicon name="link"}
					{elseif $p.p_type == 2}
						{uicon name="picture"}
					{else}
						{uicon name="message-writing"}
					{/if}
					<span class="text">{$p.p_date|hace:true}</span> 
					<span role="button" onclick="muro.like_this({$p.pub_id}, 'pub', this); return false;" class="fw-semibold text-decoration-none">{$p.likes.link}</span> 
					<span role="button" onclick="muro.show_comment_box({$p.pub_id}); return false" class="fw-semibold text-decoration-none">Comentar</span> 
					{if $tsUser->is_admod} <span class="text">{$p.p_ip}</span>{/if}
				</div>
				{include "perfil/m.perfil_muro_comments.tpl"}
			</div><!-- .Story_Foot -->
		</div><!-- .Story_Content -->
	</div>
{/foreach}