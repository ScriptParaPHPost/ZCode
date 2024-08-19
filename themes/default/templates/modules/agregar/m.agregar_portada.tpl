<script>
let portadaIMG = '{$tsDraft.b_portada}';
</script>
<div class="upform-group position-relative">
   <span class="upform-label">Portada</span>
   <div class="portada d-grid gap-2" style="grid-template-columns: repeat(3, 1fr);">
      <div class="fromPC d-flex justify-content-center align-items-center flex-column py-3" data-type="pc">
         {uicon name="laptop" size="3rem" class="pe-none"}
         <span class="fw-semiboldpe-none">Desde pc</span>
      </div>
      <div class="fromURL d-flex justify-content-center align-items-center flex-column py-3" data-type="url">
         {uicon name="link" size="3rem" class="pe-none"}
         <span class="fw-semiboldpe-none">Desde URL</span>
      </div>
      <div class="d-flex justify-content-center align-content-center">
         <div class="loadimg">
            <div class="avatar avatar-10 mx-auto placeholder placeholder-wave overflow-hidden shadow"></div>
         </div>
      </div>
   </div>
   <div class="load--field">
      <div class="upform-group"><div class="upform-group-input"></div></div>
   </div>
</div>