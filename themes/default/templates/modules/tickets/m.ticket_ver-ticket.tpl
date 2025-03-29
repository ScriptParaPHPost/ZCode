<div class="row">
	<div class="col-12 col-lg-9">
		<div class="post-header mb-3 py-3">
		   <div class="post-header--title">
		      <div class="d-flex justify-content-between align-items-center">
		         <h1 class="h3 my-2">{$tsTicket.ticket_title}</h1>
		      </div>
		      
		      <div class="d-block d-lg-flex justify-content-between align-items-center">
		         <span class="d-block"><strong>{$tsTicket.status_title}</strong> - {$tsTicket.ticket_date|hace:true}{if $tsTicket.ticket_update} | <strong>Editado</strong>{/if}</span>
		         <span></span>
		      </div>
		   </div>
		</div>
		<div class="post-contenedor">
			<span class="d-block px-4 mt-3">{$tsTicket.ticket_body}</span>
		</div>
	</div>
	<div class="col-12 col-lg-3">
		<section class="up-card">
			<div class="up-card--body p-2">
			 	<a href="{$tsConfig.url}/perfil/{$tsTicket.user_name}" class="ver-ticket-autor rounded mb-3 d-flex justify-content-start align-items-center column-gap-3 text-decoration-none">
			      {include "Picture.tpl" src=$tsTicket.user_avatar alt=$tsTicket.user_name class="object-fit-cover rounded main-bg w-100 h-100"}
			      <span class="ver-ticket-info lh-1">
				      <strong>{$tsTicket.user_name}</strong>
				      <small class="d-block">{$tsTicket.r_name}</small>
				   </span>
			   </a>
			   <h4>Ticket:</h4>
			   <ul class="ver-ticket-list">
				   {foreach [
				   	'en-espera' => 'En espera',
				   	'en-proceso' => 'En proceso',
				   	'cancelado' => 'Cancelados'
				   ] key=k item=valor}
					   <li class="d-flex justify-content-between align-items-center">
					   	<span>{$valor}:</span>
					   	<strong>{$tsTicket.status.$k.total}</strong>
					   </li>
					{/foreach}
				</ul>
			</div>
		</section>
	</div>
</div>