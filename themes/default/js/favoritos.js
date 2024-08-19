var favoritos = {
	template_favorito: '',
	template_categoria: '',
	r: [],
	counts: [],

	categoria: 'todas',
	orden: 'fecha_guardado',

	categoria_anterior: '',
	orden_anterior: '',

	printResult: function(){
		var el = $('div#resultados tbody');
		el.html('');
		$.each(this.r, function(i, favorito) {
			const { fav_id, post_id, titulo, categoria, categoria_name, imagen, url, fecha_creado, fecha_creado_formato, fecha_creado_palabras, fecha_guardado, fecha_guardado_formato, fecha_guardado_palabras, puntos, comentarios } = favorito;
			let h = `<tr id="favorito_id_${fav_id}">
				<td>
					<img src="${ZCodeApp.images.assets}/icons/cat/${imagen}" title="${categoria_name}"/>
				</td>
				<td style="text-align:left">
					<a class="titlePost" title="${titulo}" href="${url}">${titulo}</a>
				</td>
				<td title="${fecha_creado_formato}">${fecha_creado_palabras}</td>
				<td title="${fecha_guardado_formato}">${fecha_guardado_palabras}</td>
				<td class="color_green">${puntos}</td><td>${comentarios}</td>
				<td><a id="change_status" href="" onclick="favoritos.eliminar(${fav_id}, this); return false;"><img src="${ZCodeApp.tema_images}/borrar.png" alt="borrar" title="Borrar Favorito" /></a></td>
			</tr>`;
			el.append(h);
		});
	},

	printCounts: function(printCategorias){
		//Categorias
		$.each(this.counts, function(categoria, data){
			if(printCategorias)
				$('div.categoriaList ul').append(favoritos.template_categoria.replace(/__categoria__/g, categoria).replace(/__categoria_name__/g, data['name']).replace(/__count__/g, data['count']));
			else
				$('div.categoriaList ul li#cat_' + categoria + ' span.count').html(data['count']);
		});
	},

	query: function(force_no_parcial){
		//Determinacion de busqueda parcial o no
		let parcial = false;
		if(!force_no_parcial){
			//Categoria
			if(this.categoria_anterior != this.categoria){
				parcial = (this.categoria_anterior == 'todas');
			}
			//Orden
			else if(this.orden_anterior != this.orden){
				parcial = true;
			}
			//Search
			else if(this.search_q_anterior != this.search_q){
				//Calcula por la busqueda anterior si tiene que hacer una busqueda parcial
				let re = new RegExp(this.search_q_anterior);
				parcial = re.test(this.search_q);
			}
		}

		//Si esta vacio no realizo ninguna consulta
		if((parcial && this.r.length==0) || (!parcial && favoritos_data.length == 0)){
			this.categoria_anterior = this.categoria;
			this.orden_anterior = this.orden;
			this.search_q_anterior = this.search_q;
			return;
		}

		this.r = jlinq.from(parcial ? this.r : favoritos_data);

		//Categoria
		if(this.categoria != 'todas' && (!parcial || this.categoria_anterior != this.categoria))
			this.r = this.r.equals('categoria', this.categoria);

		//Search
		if(!empty(this.search_q) && (!parcial || this.search_q_anterior != this.search_q))
			this.r = this.r.contains('titulo', this.search_q);

		//Ordenar por
		if(!parcial || this.orden_anterior != this.orden || this.eliminados_force_order)
			this.r = this.r.sort((this.orden=='titulo' ? '' : '-') + this.orden); //Si ordena por titulo ASC, todos los demas DESC

		this.eliminados_force_order = false;

		this.r = this.r.select();

		this.categoria_anterior = this.categoria;
		this.orden_anterior = this.orden;
		this.search_q_anterior = this.search_q;

		this.printResult();
	},

	//Buscador
	search_q: '',
	search_q_anterior: '',
	search: function(q, event){
		tecla = (document.all) ? event.keyCode:event.which;
		if(tecla==27){ //Escape, limpio input
			q = '';
			$('#favoritos-search').val('');
		}
		if(q == this.search_q)
			return;
		//Calcula por la busqueda anterior si tiene que hacer una busqueda parcial
		this.search_q = q;
		this.query();
	},
	search_focus: function(){
		$('label[for="favoritos-search"]').hide();
	},
	search_blur: function(){
		if(empty($('#favoritos-search').val(), true))
			$('label[for="favoritos-search"]').show();
	},

	active: function(e){
		return true;
	},
	active2: function(e){
		$(e).parent().parent().children('th').children('a').removeClass('here');
		$(e).addClass('here');
	},

	eliminados_force_order: false,
	eliminados: new Array(), //Guardo los favoritos eliminados, por si quiere reactivar alguno
	eliminar: function(fav_id, obj){
	   loading.start()
		$.ajax({
			type: 'POST',
			url: ZCodeApp.url + '/favoritos-borrar.php',
			data: 'fav_id=' + fav_id + gget('key'),
			success: function(h){
				switch(h.charAt(0)){
					case '0': //Error
						mydialog.alert('Error', h.substring(3));
						break;
					case '1': //OK
						for(var i=0, s=favoritos.r.length; i<s; ++i){
							if(favoritos.r[i]['fav_id'] == fav_id){
								favoritos.eliminados.push(favoritos.r[i]);
								favoritos.counts[favoritos.r[i]['categoria']]['count']--;
								favoritos.r.splice(i, 1);
								break;
							}
						}

						for(var i=0, s=favoritos_data.length; i<s; ++i){
							if(favoritos_data[i]['fav_id'] == fav_id){
								favoritos_data.splice(i, 1);
								break;
							}
						}

						$(obj).children().attr({'src': ZCodeApp.img + '/reactivar.png',
																		'title': 'Reactivar',
																		'alt': 'reactivar'
																	});
						$(obj).parent().parent().css('opacity', '0.5');
						$(obj).removeAttr('onclick').off('click').on('click', function(){ favoritos.reactivar(fav_id, this); return false; });
/*
						//Quedaba solo un borrador
						if(borradores_data.length==1)
							$('div#borradores div#res').html('<div class="emptyData">No tienes ning&uacute;n borrador ni post eliminado</div>');
*/
						//Actualizo la impresion de contadores
						favoritos.printCounts();
						break;
				}
                loading.end()
			},
			error: function(){	
				mydialog.alert('Error', 'Hubo un error al intentar procesar lo solicitado');
                loading.end()
			}
		});
	},

	reactivar: function(fav_id, obj){
		//Recorro los eliminados en busqueda del post_id y el fav_date
		for(var i=0, s=this.eliminados.length; i<s; ++i){
			if(this.eliminados[i]['fav_id'] == fav_id){
				var post_id = this.eliminados[i]['post_id'];
				var fav_date = this.eliminados[i]['fecha_guardado'];
				break;
			}
		}
		if(i==s)
			return false; //No encontrado
        
        loading.start()
		$.ajax({
			type: 'POST',
			url: ZCodeApp.url + '/favoritos-agregar.php',
			data: 'postid=' + post_id + '&reactivar=' + fav_date + gget('key'),
			success: function(h){
				switch(h.charAt(0)){
					case '0': //Error
						mydialog.alert('Error', h.substring(3));
						break;
					case '1': //OK
						//Lo elimino de favoritos.eliminados
						for(var i=0, s=favoritos.eliminados.length; i<s; ++i){
							if(favoritos.eliminados[i]['fav_id'] == fav_id){
								var favorito = favoritos.eliminados[i];
								favoritos.eliminados.splice(i, 1);
								break;
							}
						}

						//Incremento el conteo de la categoria
						favoritos.counts[favorito['categoria']]['count']++;

						//Cambio fav_id por el nuevo valor
						favorito['fav_id'] = fav_id = h.substring(3);

						//Lo agrego a los dos arrays
						favoritos_data.push(favorito);
						favoritos.r.push(favorito);

						//Fuerzo el ordenamiento en el proximo query, ya que al agregarlo al final lo pierde
						favoritos.eliminados_force_order = true;

						$(obj).children().attr({'src': ZCodeApp.img + '/borrar.png',
																		'title': 'Borrar',
																		'alt': 'Borrar'
																	});
						$(obj).parent().parent().css('opacity', '1');
						$(obj).off('click').on('click', function(){ favoritos.eliminar(fav_id, this); return false; });
/*
						//Quedaba solo un borrador
						if(borradores_data.length==1)
							$('div#borradores div#res').html('<div class="emptyData">No tienes ning&uacute;n borrador ni post eliminado</div>');
*/
						//Actualizo la impresion de contadores
						favoritos.printCounts();
						break;
				}
                
                loading.end()
			},
			error: function(){	
				mydialog.alert('Error', 'Hubo un error al intentar procesar lo solicitado');
                loading.end()
			}
		});
	}
}

function sortObject(o){
	var sorted = {}, key, a = [];
	for(key in o)
		if(o.hasOwnProperty(key))
			a.push(key);
	a.sort();
	for(key = 0; key < a.length; key++)
		sorted[a[key]] = o[a[key]];
	return sorted;
}


$(document).ready(function(){
	//Guardo el template en una variable

	favoritos.template_favorito = '<tr id="favorito_id___fav_id__"><td><img src="'+ ZCodeApp.img+'/icons/cat/__imagen__" title="__categoria_name__"/></td><td style="text-align:left"><a class="titlePost" title="__titulo__" href="__url__">__titulo__</a></td><td title="__fecha_creado_formato__">__fecha_creado_palabras__</td><td title="__fecha_guardado_formato__">__fecha_guardado_palabras__</td><td class="color_green">__puntos__</td><td>__comentarios__</td><td><a id="change_status" href="" onclick="favoritos.eliminar(__fav_id__, this); return false;"><img src="' + ZCodeApp.img + '/borrar.png" alt="borrar" title="Borrar Favorito" /></a></td></tr>';
	favoritos.template_categoria = '<li id="cat___categoria__"><a href="" onclick="favoritos.active(this); favoritos.categoria = \'__categoria__\'; favoritos.query(); return false;">__categoria_name__</a> (<span class="count">__count__</span>)</li>';

	//Hago conteo inicial
	$.each(favoritos_data, function(i, favorito){
		if(favoritos.counts[favorito['categoria']])
			favoritos.counts[favorito['categoria']]['count']++;
		else
			favoritos.counts[favorito['categoria']] = {'name': favorito['categoria_name'], 'count':1};
	});
	favoritos.counts = sortObject(favoritos.counts);

	//Imprimo los contadores
	favoritos.printCounts(true);

	//Query inicial
	favoritos.query(true);
});

