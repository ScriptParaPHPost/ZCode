<div class="up-dropdown z-99 position-relative position-lg-absolute p-2 rounded body-bg" data-dropname="{$dropdownName}" data-dropdown="false">
    {foreach from=$items item=item}
        {if !isset($item.condition) || $item.condition}
            <a class="up-dropdown--item d-block mb-2 py-2 px-3 text-decoration-none fw-semibold position-relative rounded hover:main-bg active:main-bg hover:main-color{if isset($item.active) && $item.active} active{/if}" 
               title="{$item.title}" 
               href="{$tsConfig.url}{$item.href}"
               {if isset($item.dataTotal)} data-total="{$item.dataTotal}"{/if}>
               {$item.title}
            </a>
        {/if}
    {/foreach}
</div>