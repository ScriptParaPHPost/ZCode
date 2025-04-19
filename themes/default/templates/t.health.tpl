<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Estado del Sistema</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.1.1/dist/css/tabler.min.css" />
<script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.1.1/dist/js/tabler.min.js"></script>
</head>
<body>

	<header class="navbar navbar-expand-sm navbar-light d-print-none">
   	<div class="container-xl">
   		<h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
   		  	<a href="{$tsConfig.url}/" title="{$tsConfig.titulo} - {$tsConfig.slogan}" class="d-flex justify-content-start align-items-center gap-2">
   		    	<img src="{$tsRoutes.assets.favicon}/logo-32.webp" width="32" height="32" alt="Tabler" class="navbar-brand-image rounded"/>
   		    	<span>{$tsConfig.titulo}</span>
   		  	</a>
   		</h1>
   		<div class="navbar-nav flex-row order-md-last">
   		 	<div class="nav-item">
   		 	 	<a href="{$tsConfig.url}/perfil/Miguel92" class="nav-link d-flex lh-1 text-reset p-0">
   		 	 		<span class="avatar avatar-sm" style="background-image: url('{$tsRoutes.storage.avatar}/user1/web.webp')"></span>
   		 	 	 	<div class="d-none d-xl-block ps-2">
   		 	 	 		<div>Miguel92</div>
   		 	 	 		<div class="mt-1 small text-secondary">Administrador</div>
   		 	 	 	</div>
   		 	 	</a>
   		 	</div>
   		</div>
   	</div>
  	</header>

  	<div class="page-header bg-white py-4 mb-4">
      <div class="container">
         <div class="row g-3 align-items-center">
            <div class="col-auto">
              	<span class="status-indicator status-{if count($ZCODE_HEALTH.incidentes) > '0'}red{else}green{/if} status-indicator-animated">
               	<span class="status-indicator-circle"></span>
               	<span class="status-indicator-circle"></span>
               	<span class="status-indicator-circle"></span>
               </span>
            </div>
            <div class="col">
               <h2 class="page-title">Estado del Sistema</h2>
               <div class="text-secondary">
                  <ul class="list-inline list-inline-dots mb-0">
                  	<li class="list-inline-item">Chequeado hace {$ZCODE_HEALTH.generado|hace}</li>
                  </ul>
               </div>
            </div>
            <div class="col-md-auto ms-auto d-print-none"></div>
         </div>
     </div>
   </div>


	<div class="container">

		<div class="row row-cards">
         <div class="col-md-4">
            <div class="card">
               <div class="card-body">
                  <div class="subheader">Actualmente disponible</div>
                  <div class="h3 m-0">{$ZCODE_HEALTH.generado|elapsed_time:'dhis'}</div>
               </div>
            </div>
         </div>

         <div class="col-md-4">
           	<div class="card">
             	<div class="card-body">
               	<div class="subheader">Última verificación</div>
               	<div class="h3 m-0">{$ZCODE_HEALTH.generado|elapsed_time:'d'}</div>
             	</div>
           	</div>
         </div>

         <div class="col-md-4">
           	<div class="card">
             	<div class="card-body">
               	<div class="subheader">Incidentes</div>
               	<div class="h3 m-0">{count($ZCODE_HEALTH.incidentes)}</div>
             	</div>
           	</div>
         </div>
      </div>

      <div class="row row-cards my-4">
      	<div class="col-lg-3">
	      	<div class="row row-cards">
		      	<div class="col-lg-12">
		      		<div class="card cardbg-white rounded shadow p-3">
						   <h3 class="fs-2">Cache</h3>
						   <p class="text-secondary text-uppercase m-0 fw-bolder">{$ZCODE_HEALTH.cache}</p>
						</div>
		      	</div>
		      	<div class="col-lg-12">
		      		<div class="bg-white rounded shadow p-3">
						   <h3 class="fs-2">Base de datos</h3>
						   <p class="text-secondary text-uppercase m-0 fw-bolder">{$ZCODE_HEALTH.database}</p>
						</div>
		      	</div>
		      	<div class="col-lg-12">
		      		<div class="bg-white rounded shadow p-3">
						   <h3 class="fs-2">Latencia DB</h3>
						   <p class="text-secondary text-uppercase m-0 fw-bolder">{$ZCODE_HEALTH.latencia_bd}</p>
						</div>
		      	</div>
		      	<div class="col-lg-12">
		      		<div class="bg-white rounded shadow p-3">
						   <h3 class="fs-2">Sistemas de archivos</h3>
						   <p class="text-secondary text-uppercase m-0 fw-bolder">{$ZCODE_HEALTH.sistema_de_archivos}</p>
						</div>
		      	</div>
		      </div>
		   </div>
      	<div class="col-lg-9">
      		<div class="bg-white rounded shadow p-3">
				   <h3 class="fs-2">Composer</h3>
				   <p class="text-secondary text-uppercase fw-bolder">{$ZCODE_HEALTH.composer.estado}</p>
				   <hr>
				   <h4>Paquetes</h4>
				   <div class="row">
                  {foreach $ZCODE_HEALTH.composer.paquetes key=package item=status}
                    	<div class="col-12 col-lg-6">
                        <div class="row align-items-center p-3">
                           <div class="col text-truncate">
                              <span class="text-reset d-block">{$package}</span>
                              <div class="d-block text-secondary text-truncate mt-n1">{$status}</div>
                           </div>
                        </div>
                     </div>
               	{/foreach}
				</div>
      	</div>
      </div>
	</div>

	<footer class="footer footer-transparent d-print-none">
      <div class="container-xl">
         <ul class="list-inline list-inline-dots mb-0">
            <li class="list-inline-item">Copyright © {$smarty.now|date_format:'Y'}</li>
         </ul>
      </div>
   </footer>

</body>
</html>
