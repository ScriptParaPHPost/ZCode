{include "main_header.tpl"}
                <script type="text/javascript" src="{$tsConfig.default}/js/moderacion.js"></script>
                <div id="borradores">
					<div class="clearfix">
                    	<div class="left" style="float:left;width:210px">
                   			<div class="boxy">
                                <div class="boxy-title">
                                    <h3>Opciones</h3>
                                    <span></span>
                                </div><!-- boxy-title -->
                                <div class="boxy-content" id="admin_menu">
									{include "m.mod_sidemenu.tpl"}
                                </div><!-- boxy-content -->
                            </div>
                            {if $tsAction == 'buscador' && $tsAct == 'search'}
                                {include "m.mod_buscador_stats.tpl"}
                            {/if}
                        </div>
                        <div class="right" style="float:left;margin-left:10px;width:720px">
                            <div class="boxy" id="admin_panel">
                            	{* Q WEBA PERO NO HAY DE OTRA xD*}
                            	{if $tsAction == ''}
                            	{include "m.mod_welcome.tpl"}
                                {elseif $tsAction == 'posts'}
                            	{include "m.mod_report_posts.tpl"}
								{elseif $tsAction == 'fotos'}
                            	{include "m.mod_report_fotos.tpl"}
                                {elseif $tsAction == 'mps'}
                                {include "m.mod_report_mps.tpl"}
                                {elseif $tsAction == 'users'}
                            	{include "m.mod_report_users.tpl"}
                                {elseif $tsAction == 'banusers'}
                                {if $tsUser->is_admod || $tsUser->permisos.movub}{include "m.mod_ban_users.tpl"}{/if}
								{elseif $tsAction == 'pospelera'}
                                {if $tsUser->is_admod || $tsUser->permisos.morp}{include "m.mod_papelera_posts.tpl"}{/if}
								{elseif $tsAction == 'fopelera'}
                                {if $tsUser->is_admod || $tsUser->permisos.morf}{include "m.mod_papelera_fotos.tpl"}{/if}
								{elseif $tsAction == 'buscador'}
                                {if $tsUser->is_admod || $tsUser->permisos.moub}{include "m.mod_buscador.tpl"}{/if}
								{elseif $tsAction == 'comentarios'}
                                {if $tsUser->is_admod || $tsUser->permisos.mocc}{include "m.mod_revision_comentarios.tpl"}{/if}
								{elseif $tsAction == 'revposts'}
                                {if $tsUser->is_admod || $tsUser->permisos.mocp}{include "m.mod_revision_posts.tpl"}{/if}
                                {/if}
                            </div>
                        </div>
                    </div>
                </div>
                <div style="clear:both"></div>
                
{include "main_footer.tpl"}