<div class="row">
	<div class="col-12 col-lg-3">
	</div>
	<div class="col-12 col-lg-6">
		<form action="{$tsConfig.url}/pages-enviar.php">
		
			{include "Input.tpl" id="nombre" icon="user-male" type="text" placeholder="Nombre" required=true value=$tsUser->nick label="Nombre"}
			{include "Input.tpl" id="email" icon="mail" type="email" placeholder="noreply@example.com" required=true value=$tsUser->email label="Correo"}
			{include "Input.tpl" id="asunto" icon="tag" type="text" placeholder="Asunto" required=true label="Asunto"}
			{include "Textarea.tpl" id="mensaje" icon="pen" placeholder="Mensaje" required=true label="Mensaje"}

		</form>
	</div>
	<div class="col-12 col-lg-3"></div>
</div>