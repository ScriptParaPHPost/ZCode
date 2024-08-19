<div id="procesando"><div id="post"></div></div>

<div id="send_comment" style="display:none;">
	<div class="d-flex justify-content-center align-items-center gap-2">
		{uicon name="270-ring-with-bg" folder="spinner"}
		<span>Enviando comentario...</span>
	</div>
</div>

<div class="box-comment d-grid gap-3 p-3 mt-3">
	<div class="box-comment--avatar">
		<img src="{$tsUser->use_avatar}" class="w-100 h-100 rounded shadow"/>
	</div>
	<div class="box-comment--message">
		<div class="error"></div>
		<textarea id="boxComentar" placeholder="Agregar un comentario..."></textarea>
		<input type="hidden" id="auser_post" value="{$tsPost.post_user}" />
		<input type="button" onclick="comentario.nuevo()" class="btn button-comment" value="Comentar" id="btnsComment"/>
	</div>
</div>