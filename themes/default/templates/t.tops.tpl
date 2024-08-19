{include "main_header.tpl"}
   
   <div class="row">
      <div class="col-12 col-lg-3">
         {include "m.top_sidebar.tpl"}
      </div>
      <div class="col-12 col-lg-9">
         <div class="row row-cols-1 row-cols-lg-2{if $tsAction == 'usuarios'} row-cols-xl-3{/if}">
            {include "m.top_$tsAction.tpl"}
         </div>
      </div>
   </div>
   
{include "main_footer.tpl"}