<div class="upform-group">
	<label class="upform-label" for="{$id}">{$label}</label>
	<div class="upform-group-input{if $icon} upform-icon{/if}">
		{if $icon}
		<div class="upform-input-icon">{uicon name=$icon}</div>
		{/if}
		<input class="upform-input" type="{$type|default:'text'}" name="{$name}" id="{$id}"{if $value} value="{$value}"{/if}{if $placeholder} placeholder="{$placeholder}"{/if}{if $required} required{/if}>
		{if $showPassword}
		<div class="upform-input-icon"><div id="IWantSeePassword" title="Ver contraseña!" class="iconify unlock"></div></div>
		{/if}
	</div>
	<small class="upform-status help"></small>
	{if $html}{$html}{/if}
</div>