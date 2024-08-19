{include "main_header.tpl"}
				<div class="user-suspendido">
                    <h3>Usuario suspendido</h3>
                    <p>Hola, <strong>{$tsUser->nick}</strong> lamentamos informarte que has sido suspendido de <strong>{$tsConfig.titulo}</strong></p>
                    <h4>Raz&oacute;n:</h4>
                    <div style="width:500px">{$tsBanned.susp_causa}</div>
                    <h4>Fin de suspensi&oacute;n:</h4>
                    <strong>{if $tsBanned.susp_termina == 0}Indefinidamente{elseif $tsBanned.susp_termina == 1}Permanentemente{else}{$tsBanned.susp_termina|date_format:"%d/%m/%Y a las %H:%M:%S"}hs{/if}</strong>
                    <h4>Fecha actual:</h4>
                    {$smarty.now|date_format:"%d/%m/%Y %H:%M:%S"}hs.
                </div>
{include "main_footer.tpl"}
                                    