<div id="procesando"><div id="post"></div></div>

<div class="box-comment p-2 border-0 ms-5 mb-3" id="boxComentar{$c.cid}" style="display: none;">
	
	<div class="box-comment--message{$c.cid} w-100">
		<div class="error"></div>
		<textarea class="w-100 boxResponder px-1" style="height:48px" data-reply="{$c.cid}" placeholder="Agrega una respuesta..."></textarea>
		<input type="hidden" id="auser_post" value="{$tsPost.post_user}" />
      <input type="hidden" id="respuesta_{$c.cid}" name="respuesta_{$c.cid}" value="{$c.cid}" />
      <input type="button" class="btn button-comment" data-button="{$c.cid}" onclick="comentario.responser_comentario({$c.cid})" value="Responder"/>
	</div>
</div>