<div class="upform-group">
	<label class="upform-label" for="{$id}">{$label}</label>
	<div class="upform-group-input{if $icon} upform-icon{/if}">
		{if $icon}
		<div class="upform-input-icon">{uicon name=$icon}</div>
		{/if}
		<textarea class="upform-textarea" name="{$name}" id="{$id}"{if $placeholder} placeholder="{$placeholder}"{/if}{if $required} required{/if}>{$value}</textarea>
	</div>
	<small class="upform-status help"></small>
</div>