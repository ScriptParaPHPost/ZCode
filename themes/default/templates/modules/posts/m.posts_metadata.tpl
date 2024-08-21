<div class="post-metadata">
	<div style="display:none" class="mensajes"></div>
	
	<div class="d-flex justify-content-between align-items-center column-gap-2 py-3">
		<div class="d-flex justify-content-start align-items-center column-gap-2 py-3">
		
		   {if !$tsPost.post_vote && $tsPost.post_user != $tsUser->uid}
		      <span role="button" onclick="votar.votar_post(1)" id="vp_pos" class="avatar avatar-2 d-flex justify-content-center align-items-center" title="Votar positivo">
		      	{uicon name="thumbs-up" size="3rem" style="color:#6f8f52"}
			   </span>
		      <span role="button" onclick="votar.votar_post(2)" id="vp_neg" class="avatar avatar-2 d-flex justify-content-center align-items-center" title="Votar negativo">
		      	{uicon name="thumbs-down" size="3rem" style="color:#B92626"}
		      </span>
		   {/if}

		   <span role="button" onclick="$('#vp_toggle').toggle('fast')" id="vp_total" class="badge" style="background:#{if $tsPost.post_puntos < 0}B92626{else}6f8f52{/if};color:#fff;" title="Total votos">Votos {$tsPost.post_puntos}</span>
		</div>
		<div class="post-acciones">
			{if !$tsUser->is_member}
				<span class="btn follow_user_post" role="button" onclick="registro_load_form(); return false"><span class="icons follow_post follow">Seguir Post</span></span>
			{elseif $tsPost.post_user != $tsUser->uid}
				<div{if !$tsPost.follow} style="display: none;"{/if}>
					<span role="button" class="btn unfollow_post" onclick="notifica.followed('unfollow', 'post', {$tsPost.post_id}, notifica.inPostHandle, $(this).children('span'))"><span>Dejar de seguir</span></span>
				</div>
				<div{if $tsPost.follow > 0} style="display: none;"{/if}>
					<span role="button" class="btn follow_post" onclick="notifica.followed('follow', 'post', {$tsPost.post_id}, notifica.inPostHandle, $(this).children('span'))"><span>Seguir Post</span></span>
				</div>
			{/if}
		</div>
	</div>
	
	<div id="vp_toggle" style="display: none;">
	   {foreach from=$tsPost.puntos item=v key=i}
	      <a href="{$tsConfig.url}/perfil/{$v.user_name}" style="color:#{if $v.cant == 2}B92626{else}6f8f52{/if};" title="Vot&oacute; {if $v.cant == 2}negativo{else}positivo{/if}" class="fw-semibold text-decoration-none">{$v.user_name|verificado}</a>{if $tsPost.total_votos > $i+1}, {/if}
	   {foreachelse}
	   	<div class="empty">Nadie voto</div>
	   {/foreach}
	</div>

	
</div>