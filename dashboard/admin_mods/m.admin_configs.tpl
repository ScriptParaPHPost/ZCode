<div class="boxy-title">
    <h3>Caracter&iacute;sticas y Opciones</h3>
</div>
<div id="res" class="boxy-content">
   {if $tsSave}<div class="empty empty-success">Configuraciones guardadas</div>{/if}
   <form action="" method="post" autocomplete="off">
      <fieldset>
         <legend>Configuraci&oacute;n del Sitio</legend>
         <dl>
            <dt><label for="ai_titulo">Nombre del Sitio:</label></dt>
            <dd><input type="text" id="ai_titulo" name="titulo" maxlength="24" value="{$tsConfig.titulo}" /></dd>
         </dl>
         <dl>
            <dt><label for="ai_slogan">Descripci&oacute;n del Sitio:</label></dt>
            <dd><input type="text" id="ai_slogan" name="slogan" maxlength="32" value="{$tsConfig.slogan}" /></dd>
         </dl>
         <dl>
            <dt><label for="ai_url">Direcci&oacute;n del sitio:</label></dt>
            <dd><input type="text" id="ai_url" name="url" maxlength="32" value="{$tsConfig.url}" /></dd>
         </dl>
         <dl>
            <dt><label for="leaving">Saliendo del sitio:</label></dt>
            <dd>{html_radios name="leaving" values=[1, 0] id="leaving" output=['Activar', 'Desactivar'] selected=$tsConfig.leaving class="radio"}</dd>
         </dl>
         <dl>
            <dt><label for="ai_offline">Modo mantenimiento:</label><span>Esto har&aacute; al Sitio inaccesible a los usuarios. Si quiere, tambi&eacute;n puede introducir un breve mensaje (255 caracteres) para mostrar.</span></dt>
            <dd>
					{html_radios name="offline" values=[1, 0] id="offline" output=['Si', 'No'] selected=$tsConfig.offline class="radio"}
					<br />
               <input type="text" name="offline_message" id="ai_offline" value="{$tsConfig.offline_message}" />
            </dd>
         </dl>
         <hr />
         <dl>
            <dt><label for="ai_active">Usuario online:</label><span>Tiempo que debe trascurrir para considerar que un usuario est&aacute; en linea.</span></dt>
            <dd>
               <div class="input-group" style="width:100px!important">
                  <input type="text" id="ai_active" name="c_last_active" maxlength="2" value="{$tsConfig.c_last_active}" /> 
                  <span>min.</span>
               </div>
            </dd>
         </dl>
         <dl>
            <dt><label for="ai_stats_cache">Estad&iacute;sticas en buffer:</label><span>Tiempo que debe trascurrir para actualizar las estad&iacute;sticas del sitio.</span></dt>
            <dd>
               <div class="input-group" style="width:100px!important">
                  <input type="text" id="ai_stats_cache" name="c_stats_cache" maxlength="2" value="{$tsConfig.c_stats_cache}" /> 
                  <span>min.</span>
               </div>
            </dd>
         </dl>
         <dl>
            <dt><label for="ai_sess_ip">Login por IP:</label><span>Por seguridad cada que un usuario cambie de IP se le pedir&aacute; loguearse nuevamente.</span></dt>
            <dd>
					{html_radios name="c_allow_sess_ip" id="ai_sess_ip" values=[1, 0] output=['Si', 'No'] selected=$tsConfig.c_allow_sess_ip class="radio"}
            </dd>
         </dl>
         <dl>
            <dt><label for="ai_count_guests">Los visitantes suman estad&iacute;sticas</label><span>Contar a los visitantes en las estad&iacute;sticas generales.</span></dt>
            <dd>
					{html_radios name="c_count_guests" id="ai_count_guests" values=[1, 0] output=['Si', 'No'] selected=$tsConfig.c_count_guests class="radio"}
            </dd>
         </dl>
         <dl>
            <dt><label for="ai_hits_guest">Los visitantes suman visitas</label><span>Contar las visitas de los visitantes en posts y fotos.</span></dt>
            <dd>
					{html_radios name="c_hits_guest" id="ai_hits_guest" values=[1, 0] output=['Si', 'No'] selected=$tsConfig.c_hits_guest class="radio"}
            </dd>
         </dl>
         <hr />
         <dl>
            <dt><label for="ai_firma">Firma de usuario:</label><span>Las firmas de los usuarios son visibles en los post.</span></dt>
            <dd>
					{html_radios name="c_allow_firma" id="ai_firma" values=[1, 0] output=['Si', 'No'] selected=$tsConfig.c_allow_firma class="radio"}
            </dd>
         </dl>
         <dl>
            <dt><label for="ai_upload">Carga externa:</label><span>Si cuentas con un servidor de pago o la librer&iacute;a CURL puedes subir im&aacute;genes remotamente a imgur.com</span></dt>
            <dd>
					{html_radios name="c_allow_upload" id="ai_upload" values=[1, 0] output=['Si', 'No'] selected=$tsConfig.c_allow_upload class="radio"}
            </dd>
         </dl>
         <dl>
            <dt><label for="ai_avatar">Activar avatar:</label><span>Los usuarios podr&aacute;n usar un gif perzonalizado como avatar.</span></dt>
            <dd>
               {html_radios name="c_avatar" id="ai_avatar" values=[1, 0] output=['Si', 'No'] selected=$tsConfig.c_avatar class="radio"}
            </dd>
         </dl>
         <dl>
            <dt><label for="ai_portal">Activar portal:</label><span>Los usuarios podr&aacute;n tener un inicio perzonalizado.</span></dt>
            <dd>
					{html_radios name="c_allow_portal" id="ai_portal" values=[1, 0] output=['Si', 'No'] selected=$tsConfig.c_allow_portal class="radio"}
            </dd>
         </dl>
         <dl>
            <dt><label for="ai_fotos_private">Secci&oacute;n de fotos oculta</label><span>Si est&aacute; activado, los visitantes no podr&aacute;n ver la secci&oacute;n fotos.</span></dt>
            <dd>
					{html_radios name="c_fotos_private" id="ai_fotos_private" values=[1, 0] output=['Si', 'No'] selected=$tsConfig.c_fotos_private class="radio"}
            </dd>
         </dl>
         <dl>
            <dt><label for="ai_see_mod">Vista moderativa amplia</label><span>Si est&aacute; activado, el equipo de moderaci&oacute;n podr&aacute; ver, diferenciado por colores, los distintos estados de los posts.</span></dt>
            <dd>
					{html_radios name="c_see_mod" id="ai_see_mod" values=[1, 0] output=['Si', 'No'] selected=$tsConfig.c_see_mod class="radio"}
            </dd>
         </dl>
         <dl>
            <dt><label for="ai_desapprove_post">Revisi&oacute;n de posts tras su publicaci&oacute;n</label><span>Si est&aacute; activado, el equipo de moderaci&oacute;n deber&aacute; aprobar un post antes de que &eacute;ste sea publicado.</span></dt>
            <dd>
					{html_radios name="c_desapprove_post" id="ai_desapprove_post" values=[1, 0] output=['Si', 'No'] selected=$tsConfig.c_desapprove_post class="radio"}
            </dd>
         </dl>
         <hr />
         <dl>
            <dt>
               <label for="ai_keep_points">Mantener los puntos:</label><span>Al momento de recargar los puntos, si est&aacute; habilitado se conservar&aacute;n los puntos que el usuario no haya gastado los puntos en el d&iacute;, si est&aacute; deshabilitado, se restablecer&aacute;n a los puntos asignados para cada rango.</span>
            </dt>
            <dd>
					{html_radios name="c_keep_points" id="ai_keep_points" values=[1, 0] output=['Si', 'No'] selected=$tsConfig.c_keep_points class="radio"}
            </dd>
         </dl>
         <dl>
            <dt><label for="ai_live">Notificaciones Live:</label><span>Los usuarios podr&aacute;n ver en tiempo real sus notificaciones. (Esta opci&oacute;n puede consumir un poco m&aacute;s de recursos.)</span></dt>
            <dd>
					{html_radios name="c_allow_live" id="ai_live" values=[1, 0] output=['Si', 'No'] selected=$tsConfig.c_allow_live class="radio"}
            </dd>
         </dl>
         <dl>
            <dt><label for="ai_max_nots">M&aacute;ximo de notificaciones:</label><span>Cuantas notificaciones puede recibir un usuario.</span></dt>
            <dd>
               <input type="text" id="ai_max_nots" name="c_max_nots" style="width:100px!important" maxlength="3" value="{$tsConfig.c_max_nots}" />
            </dd>
         </dl>
         <dl>
            <dt><label for="ai_max_acts">M&aacute;ximo de actividades:</label><span>Cuantas actividades puede registrar un usuario.</span></dt>
            <dd>
               <input type="text" id="ai_max_acts" name="c_max_acts" style="width:100px!important" maxlength="3" value="{$tsConfig.c_max_acts}" />
            </dd>
         </dl>
         <hr />
         <dl>
            <dt><label for="ai_max_post">Posts por p&aacute;gina:</label><span>N&uacute;mero m&aacute;ximo de posts a mostrar en cada p&aacute;gina de la portada.</span></dt>
            <dd>
               <input type="text" id="ai_max_post" name="c_max_posts" style="width:100px!important" maxlength="3" value="{$tsConfig.c_max_posts}" />
            </dd>
         </dl>
         <dl>
            <dt><label for="ai_max_com">Comentarios por post:</label><span>N&uacute;mero m&aacute;ximo de comentarios por p&aacute;gina en los post.</span></dt>
            <dd>
               <input type="text" id="ai_max_com" name="c_max_com" style="width:100px!important" maxlength="3" value="{$tsConfig.c_max_com}" />
            </dd>
         </dl>
         <dl>
            <dt>
               <label for="ai_allow_points" class="qtip" title="Si introducimos '0', se permitir&aacute; dar los puntos definidos por el rango del usuario. <br /> <br />  Si introducimos '-1', se podr&aacute;n dar todos los puntos que el usuario tenga para dar hoy. <br /> <br /> Introduciendo un n&uacute;mero superior a 0, todos los usuarios sin importar su rango, tend&aacute;n esa cantidad para dar.">Puntos por post:</label><span>N&uacute;mero m&aacute;ximo de puntos que permitimos dar en los posts. </span>
            </dt>
            <dd>
               <input type="text" id="ai_allow_points" name="c_allow_points" style="width:100px!important" maxlength="3" value="{$tsConfig.c_allow_points}" />
             </dd>
         </dl>
         <dl>
            <dt><label for="ai_sum_p">Los votos suman puntos:</label><span>Cada voto positivo en un comentario es un punto m&aacute;s para el usuario. <strong>Nota:</strong> Los votos negativos no restan puntos</span></dt>
            <dd>
					{html_radios name="c_allow_sump" id="ai_sum_p" values=[1, 0] output=['Si', 'No'] selected=$tsConfig.c_allow_sump class="radio"}
            </dd>
         </dl>
         <hr />
         <dl>
            <dt><label for="ai_nfu">Cambio de rango:</label><span>Un usuario sube de rango cuando obtiene los puntos m&iacute;nimos en:</span></dt>
            <dd>
					{html_radios name="c_newr_type" id="ai_nfu" values=[1, 0] output=['Todos sus post', 'Solo en un post'] selected=$tsConfig.c_newr_type class="radio"}
            </dd>
         </dl>
         <p><input type="submit" name="save" value="Guardar Cambios" class="btn_g" /></p>
      </fieldset>
   </form>
</div>