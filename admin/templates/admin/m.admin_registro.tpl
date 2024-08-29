<div class="boxy-title">
	<h3>Opciones del registro</h3>
</div>
<div id="res" class="boxy-content">
	{if $tsSave}<div class="empty empty-success">Configuraciones guardadas</div>{/if}
	<form action="" method="post" autocomplete="off">
      <fieldset>
		  	<legend>Configuraci&oacute;n del Registro</legend>
		  	<dl>
            <dt><label for="pkey">reCaptcha p&uacute;blica v3</label><span>Clave p&uacute;blica de <a targe="_blank" href="https://www.google.com/recaptcha/admin">reCatpcha</a>.</span></dt>
            <dd><input type="text" id="pkey" name="pkey" value="{$tsConfig.pkey}" /></dd>
         </dl>
         <dl>
            <dt><label for="skey">reCaptcha secreta v3</label><span>Clave privada de <a targe="_blank" href="https://www.google.com/recaptcha/admin">reCatpcha</a>.</span></dt>
            <dd><input type="text" id="skey" name="skey" value="{$tsConfig.skey}" /></dd>
         </dl>
         <hr />
		  	<dl>
		  		<dt><label for="ai_edad">Edad requerida:</label><span>A partir de que edad los usuarios pueden registrarse.</span></dt>
            <dd>
               <div class="input-group"  style="width:150px!important">
                  <input type="text" id="ai_edad" name="c_allow_edad" maxlength="2" value="{$tsConfig.c_allow_edad}" /> 
                  <span>a&ntilde;os.</span>
               </div>
            </dd>
         </dl>
			<dl>
			 	<dt><label for="ai_met_welcome">Mensaje de Bienvenida:</label><br /><span id="desc_message_welcome" {if $tsConfig.c_met_welcome==0 }style="display:none;" {/if}> <br /> [usuario] => Nombre del registrado <br /> [welcome] => Bienvenido/a depende del sexo <br /> [web] => Nombre de esta web <br /> <br />(Se aceptan BBCodes y Smileys)</span></dt>
	         <dd>
					{html_options name='c_met_welcome' id='c_met_welcome' options=[0 => 'No dar bienvenida', 1 => 'Muro', 2 => 'Mensaje privado', 3 => 'Aviso'] selected=$tsConfig.c_met_welcome class="select up-select--jquery"}
	            <br />
	            <textarea name="c_met_welcome" id="ai_met_welcome" style="height: 100px; {if $tsConfig.c_met_welcome == 0} display:none; {/if}">{$tsConfig.c_message_welcome}</textarea>
	         </dd>
	      </dl>
			<dl>
				<dt><label for="ai_reg_active">Registro abierto:</label><span>Permitir el registro de nuevos usuarios</span></dt>
				<dd>
					{html_radios name="c_reg_active" id="ai_reg_active" values=[1, 0] output=['Si', 'No'] selected=$tsConfig.c_reg_active class="radio"}
				</dd>
			</dl>
			<dl>
				<dt><label for="ai_reg_activate">Activar usuarios:</label><span>Activar autom&aacute;ticamente la cuenta de usuario.</span></dt>
				<dd>
					{html_radios name="c_reg_activate" id="ai_reg_activate" values=[1, 0] output=['Si', 'No'] selected=$tsConfig.c_reg_activate class="radio"}
				</dd>
		  </dl>
		<dl>
		  <p><input type="submit" name="save" value="Guardar Cambios" class="btn_g"/></p>
	 </fieldset>
	 </form>
</div>