{include "m.global_ads_300.tpl"}
<br>
{if $tsInfo.p_socials != ''}
	<section class="up-card">
		<div class="up-card--header" icon="true">
			<div class="up-header--icon">
				{uicon name="cloud"}
			</div>
			<div class="up-header--title">
				<span>Redes Sociales</span>
			</div>
		</div>
		<div class="up-card--body d-grid gap-3 p-2" style="grid-template-columns: repeat(5, 1fr);">

		  	{assign var="redesConContenido" value=[]}
	      {assign var="redesSinContenido" value=[]}

	      {foreach $tsRedes key=name item=red}
	         {if $tsInfo.p_socials.$name != ''}
	            {assign var="redConContenido" value="<a class='sitio icon_social icon_$name' target='_blank' href='{$red.url}/{$tsInfo.p_socials.$name}' title='{$red.nombre}' class=''></a>"}
	            {append var="redesConContenido" value=$redConContenido}
	         {else}
	            {assign var="redSinContenido" value="<span class='sitio icon_social icon_$name icon_off'></span>"}
	            {append var="redesSinContenido" value=$redSinContenido}
	         {/if}
	      {/foreach}

	      {foreach $redesConContenido as $redConContenidoItem}
	         {$redConContenidoItem}
	      {/foreach}

	      {foreach $redesSinContenido as $redSinContenidoItem}
	         {$redSinContenidoItem}
	      {/foreach}
		</div>
	</section>
{/if}

<section class="up-card">
	<div class="up-card--header" icon="true">
		<div class="up-header--icon">{uicon name="medal"}</div>
		<div class="up-header--title"><span>Medallas</span></div>
		<div class="up-header--icon">{$tsGeneral.m_total}</div>
	</div>
	<div class="up-card--body">
		{if $tsGeneral.m_total}
		<div class="medallas orden-21 d-grid gap-2 place-center py-2">
			{foreach from=$tsGeneral.medallas item=m}
				<div class="medalla orden-item d-flex justify-content-center align-items-center avatar avatar-3">
					<img src="{$m.m_image}" title="{$m.m_title} - {$m.m_description}"/>
				</div>
			{/foreach}
		</div>
		{else}
		 	<div class="empty">No tiene medallas</div>
		{/if}
	</div>
	{if $tsGeneral.m_total >= 21}
	<div class="up-card--footer">
		<span role="button" onclick="perfil.load_tab('medallas', $('#medallas'));" class="fw-semibold text-decoration-none">Ver m&aacute;s &raquo;</span>
	</div>
	{/if}
</section>

<section class="up-card">
	<div class="up-card--header" icon="true">
		<div class="up-header--icon">{uicon name="users"}</div>
		<div class="up-header--title"><span>Seguidores</span></div>
		<div class="up-header--icon user_follow_count">{$tsInfo.stats.user_seguidores}</div>
	</div>
	<div class="up-card--body reload_followed">
		{if $tsGeneral.seguidores.data}
			<div class="users orden-21 d-grid gap-2 place-center py-2">
				{foreach from=$tsGeneral.seguidores.data item=s}
					<a href="{$tsConfig.url}/perfil/{$s.user_name}" class="text-decoration-none user orden-item overflow-hidden d-flex justify-content-center align-items-center avatar avatar-3 rounded translucent-bg border">
						<img loading="lazy" src="{$s.avatar}" class="w-100 h-100"/>
					</a>
				{/foreach}
			</div>
		{else}
			<div class="empty">No tiene seguidores</div>
		{/if}
	</div>
	{if $tsGeneral.seguidores.total >= 21}
	<div class="up-card--footer">
		<span role="button" onclick="perfil.load_tab('seguidores', $('#seguidores'));" class="fw-semibold text-decoration-none">Ver m&aacute;s &raquo;</span>
	</div>
	{/if}
</section>

<section class="up-card">
	<div class="up-card--header" icon="true">
		<div class="up-header--icon">{uicon name="users"}</div>
		<div class="up-header--title"><span>Siguiendo</span></div>
		<div class="up-header--icon">{$tsInfo.stats.user_seguidos}</div>
	</div>
	<div class="up-card--body">
		{if $tsGeneral.siguiendo.data}
			<div class="users orden-21 d-grid gap-2 place-center py-2">
				{foreach from=$tsGeneral.siguiendo.data item=s}
					<a href="{$tsConfig.url}/perfil/{$s.user_name}" class="text-decoration-none user orden-item overflow-hidden d-flex justify-content-center align-items-center avatar avatar-3 rounded translucent-bg border">
						<img loading="lazy" src="{$s.avatar}" class="w-100 h-100"/>
					</a>
				{/foreach}
			</div>
		{else}
			<div class="empty">No sigue usuarios</div>
		{/if}
	</div>
	{if $tsGeneral.siguiendo.total >= 21}
	<div class="up-card--footer">
		<span role="button" onclick="perfil.load_tab('siguiendo', $('#siguiendo'));" class="fw-semibold text-decoration-none">Ver m&aacute;s &raquo;</span>
	</div>
	{/if}
</section>

{if $tsInfo.can_hits}
	<section class="up-card">
		<div class="up-card--header" icon="true">
			<div class="up-header--icon">{uicon name="undo-history"}</div>
			<div class="up-header--title"><span>&Uacute;ltimas visitas</span></div>
			<div class="up-header--icon">{$tsInfo.visitas_total}</div>
		</div>
		<div class="up-card--body">
			{if $tsInfo.visitas}
				<div class="users orden-21 d-grid gap-2 place-center py-2">
					{foreach from=$tsInfo.visitas item=s}
						<a href="{$tsConfig.url}/perfil/{$s.user_name}" class="text-decoration-none user orden-item overflow-hidden d-flex justify-content-center align-items-center avatar avatar-3 rounded translucent-bg border">
							<img loading="lazy" src="{$s.avatar}" class="w-100 h-100"/>
						</a>
					{/foreach}
				</div>
			{else}
				<div class="empty">No tiene visitas</div>
			{/if}
		</div>
	</section>
{/if}