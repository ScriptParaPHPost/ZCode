<div class="boxy-title">
   <h3>Configuración SEO</h3>
</div>
<div id="res" class="boxy-content">
   {if $tsSave}<div class="empty empty-success">Configuraciones guardadas</div>{/if}
   <form action="" method="post" autocomplete="off">
      <fieldset>
         <dl>
            <dt><label for="titulo">Titulo:</label><br><small>Debe contener entre 50 a 60 caracteres!</small></dt>
            <dd><input type="text" id="titulo" name="titulo" minlength="10" maxlength="60" value="{$tsSeo.seo_titulo}" /></dd>
         </dl>
         <dl>
            <dt><label for="descripcion">Decripción:</label><br><small>Debe contener entre 150 a 160 caracteres!</small></dt>
            <dd><textarea name="descripcion" id="descripcion">{$tsSeo.seo_descripcion}</textarea></dd>
         </dl>
         <dl>
            <dt><label for="keywords">Palabras claves:</label></dt>
            <dd><input type="text" id="keywords" name="keywords" value="{$tsSeo.seo_keywords}" /></dd>
         </dl>
         <dl>
            <dt><label for="robots">Activar rasteadores:</label><small>Activar los rastreadores de los motores de búsqueda si pueden o no indexar una página.</small></dt>
            <dd>
					{html_radios name="robots" id="robots" values=[1, 0] output=['Si', 'No'] selected=$tsSeo.seo_robots class="radio"}
            </dd>
         </dl>
         <dl>
            <dt><label for="robots_name">Tipos de rasteadores:</label><small>indica a los buscadores que no muestren esa página en los resultados de búsqueda.</small></dt>
            <dd style="display:grid;grid-template-columns: repeat(2, 1fr);gap: .5rem">
					<div>
						<label class="input-group-text" for="robots_name">Name</label>
						{html_options name='robots_data[name]' id='robots_name' options=[0 => 'robots', 1 => 'googlebot', 2 => 'googlebot-news'] selected=$tsSeo.robots_name class="form-select"}
					</div>
					<div>
						<label class="input-group-text" for="robots_name">Content</label>
						{html_options name='robots_data[content]' id='robots_content' options=[0 => 'index', 1 => 'follow', 2 => 'noindex', 3 => 'nofollow', 4 => 'nosnippet', 5 => 'index, follow', 6 => 'index, nofollow', 7 => 'noindex, follow', 8 => 'noindex, nofollow'] selected=$tsSeo.robots_content class="form-select"}
					</div>
				</dd>
         </dl>
         <dl>
            <dt><label for="portada">Portada:</label></dt>
            <dd><input type="text" id="portada" name="portada" value="{$tsSeo.seo_portada}" /></dd>
         </dl>
         <dl>
            <dt><label for="favicon">Icono del sitio:</label></dt>
            <dd><input type="text" id="favicon" name="favicon" value="{$tsSeo.seo_favicon}" /></dd>
         </dl>
         <dl>
            <dt><label for="images">Otros iconos:</label><br><small>16x16, 32x32, 64x64, etc</small><br><button type="button" class="btn btnOk" onclick="favs.add()">Añadir</button></dt>
            <dd id="addFavs">
            	{foreach $tsSeo.seo_images key=i item=px}
               	<div class="input-group w-100">
                     <span class="input-group-text text-center d-block" style="width: 90px;" id="pixeles">{$i}x{$i}</span>
               		<input type="text" id="images" name="images[{$i}]" value="{$tsSeo.seo_images.$i}" />
               		<button type="button" class="btn btnOk" onclick="$(this).parent().remove()">Quitar</button>
               	</div>
            	{/foreach}
            </dd>
         </dl>
         <div class="search-results">
	        	<div class="result">
               <img class="image" src="{$tsSeo.seo_portada}" alt="{$tsSeo.seo_titulo}">
	            <span class="title">{$tsSeo.seo_titulo}</span>
	            <span class="url">{$tsConfig.url}</span>
	            <span class="description">{$tsSeo.seo_descripcion}</span>
	        	</div>
	      </div>
         <style>
         	.input-group {
         		display: flex;
         		justify-content: flex-start;
         		align-items: center;
         		margin-bottom: 0.325rem;
         		gap: .5rem;
         	}
         	.input-group > .input-group-text {
         		font-weight: 500;
         		width: 50px;
         	}
         	.input-group > input[type="text"] {
         		padding: 0.3rem!important;
         	}
            .search-results {
               width: 400px;
               margin: 1rem auto;
               border: 1px solid #CCC;
               padding: 0.5rem;
               border-radius: .325rem;
               line-height: 1.25rem;
            }
            .search-results img {
            	width: 100%;
            	height: 200px;
            	object-fit: cover;
               border-radius: .325rem;
               margin-bottom: 0.5rem;
            }
            .search-results span {
            	display: block;
            }
            .search-results .title {
            	font-weight: 600;
            	font-size: 1.125rem;
            }
            .search-results .url {
            	font-style: italic;
            	color: #2375C1;
            }
            .search-results .descripcion {
            	line-height: 1rem;
            	color: #888;
            }
         </style>
         <script>
            const preview = true;
         </script>
         <p><input type="submit" value="Guardar Cambios" class="btn btnOk" /></p>
      </fieldset>
   </form>
</div>