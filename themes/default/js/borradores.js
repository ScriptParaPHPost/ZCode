var borradores = {
	template_borrador: '',
	r: [],
	counts: [],

	filtro: 'todos',
	categoria: 'todas',
	orden: 'titulo',

	filtro_anterior: '',
	categoria_anterior: '',
	orden_anterior: '',

	printResult: function(){
		var el = $('ul#resultados-borradores');
		el.html('');
		$.each(this.r, function(i, borrador) {
			const { id, titulo, categoria, imagen, fecha_guardado, causa, categoria_name, tipo, url ,fecha_print } = borrador;

			let onclick = (borrador['tipo'] == 'eliminados') ? `onclick="borradores.show_eliminado(${id}); return false;"` : '';
			let h = `<li id="borrador_id_${id}">
			<a title="${categoria_name}" class="categoriaPost ${categoria} ${tipo}" href="${url}" ${onclick} style="background-image:url(${ZCodeApp.images.assets}/icons/cat/${imagen})">${titulo}</a>
				<span class="causa">Causa: ${causa}</span>
				<span class="gray">&Uacute;ltima vez guardado el ${fecha_guardado}</span> <a style="float:right" href="" onclick="borradores.eliminar(${id}, true); return false;"><span title="Eliminar Borrador">&times</span></a>
			</li>`;
			el.append(h);
			if(borrador['tipo']!='eliminados') $(`ul#resultados-borradores li#borrador_id_${id} span.causa`).remove();
		});
	},

	printCounts: function(printCategorias){
		//Filtros
		$('ul#borradores-filtros li#todos span.count').html(this.counts['todos']);
		$('ul#borradores-filtros li#borradores span.count').html(this.counts['borradores']);
		$('ul#borradores-filtros li#eliminados span.count').html(this.counts['eliminados']);

		//Categorias
		$('ul#borradores-categorias li#todas span.count').html(this.counts['todos']);
		$.each(this.counts['categorias'], function(categoria, data){
			if(printCategorias)
				$('ul#borradores-categorias').append('<li id="' + categoria + '"><span class="cat-title"><a href="" onclick="borradores.active(this); borradores.categoria = \'' + categoria + '\'; borradores.query(); return false;">' + data['name'] + '</a></span> <span class="count">' + data['count'] + '</span></li>');
			else
				$('ul#borradores-categorias li#' + categoria + ' span.count').html(data['count']);
		});
	},

	query: function(force_no_parcial){
		//force_no_parcial[boolean] = true => No hace la busqueda parcial. false -> Dependiendo del caso, determina si usa la busqueda parcial o no.

		//Determinacion de busqueda parcial o no
		var parcial = false;
		if(!force_no_parcial){
			//Filtro
			if(this.filtro_anterior != this.filtro){
				parcial = (this.filtro_anterior == 'todos');
			}
			//Categoria
			else if(this.categoria_anterior != this.categoria){
				parcial = (this.categoria_anterior == 'todas');
			}
			//Orden
			else if(this.orden_anterior != this.orden){
				parcial = true;
			}
			//Search
			else if(this.search_q_anterior != this.search_q){
				//Calcula por la busqueda anterior si tiene que hacer una busqueda parcial
				var re = new RegExp(this.search_q_anterior);
				parcial = re.test(this.search_q);
			}
		}

		//Si esta vacio no realizo ninguna consulta
		if((parcial && this.r.length==0) || (!parcial && borradores_data.length == 0)){
			this.filtro_anterior = this.filtro;
			this.categoria_anterior = this.categoria;
			this.orden_anterior = this.orden;
			this.search_q_anterior = this.search_q;
			return;
		}

		this.r = jlinq.from(parcial ? this.r : borradores_data);

		//Filtro
		if(this.filtro != 'todos' && (!parcial || this.filtro_anterior != this.filtro))
			this.r = this.r.equals('tipo', this.filtro);

		//Categoria
		if(this.categoria != 'todas' && (!parcial || this.categoria_anterior != this.categoria))
			this.r = this.r.equals('categoria', this.categoria);

		//Search
		if(!empty(this.search_q) && (!parcial || this.search_q_anterior != this.search_q))
			this.r = this.r.contains('titulo', this.search_q);

		//Ordenar por
		if(!parcial || this.orden_anterior != this.orden)
			this.r = this.r.sort(this.orden);

		this.r = this.r.select();

		this.filtro_anterior = this.filtro;
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
			$('#borradores-search').val('');
		}
		if(q == this.search_q)
			return;
		//Calcula por la busqueda anterior si tiene que hacer una busqueda parcial
		this.search_q = q;
		this.query();
	},
	search_focus: function(){
		$('label[for="borradores-search"]').hide();
	},
	search_blur: function(){
		if(empty($('#borradores-search').val()))
			$('label[for="borradores-search"]').show();
	},

	active: function(e){
		$(e).parent().parent().parent().children('li').removeClass('active');
		$(e).parent().parent().addClass('active');
	},

	eliminar: function(id, dialog){
		mydialog.close();
		if(dialog){
			mydialog.show();
			mydialog.title('Eliminar Borrador');
			mydialog.body('&iquest;Seguro que deseas eliminar este borrador?');
			mydialog.buttons(true, true, 'SI', 'borradores.eliminar(' + id + ', false)', true, false, true, 'NO', 'close', true, true);
			mydialog.center();
		}else{
		  loading.start()
			$.ajax({
				type: 'POST',
				url: ZCodeApp.url + '/borradores-eliminar.php',
				data: 'borrador_id=' + id,
				success: function(h){
					switch(h.charAt(0)){
						case '0': //Error
							mydialog.alert('Error', h.substring(3));
							break;
						case '1':
							$('li#borrador_id_' + id).fadeOut('normal', function(){ $(this).remove(); });
							//Quedaba solo un borrador
							if(borradores_data.length==1)
								$('div#borradores div#res').html('<div class="emptyData">No tienes ning&uacute;n borrador ni post eliminado</div>');

							//Lo elimino de borradores_data
							for(var i=0; i<borradores_data.length; i++){
								if(borradores_data[i]['id']==id){
									//Hago los descuentos de contadores
									borradores.counts['todos']--;
									borradores.counts[borradores_data[i]['tipo']]--;
									borradores.counts['categorias'][borradores_data[i]['categoria']]['count']--;

									borradores_data.splice(i, 1);
									break;
								}
							}

							//Lo elimino de borradores.r
							for(var i=0; i<borradores.r.length; i++){
								if(borradores.r[i]['id']==id){
									borradores.r.splice(i, 1);
									break;
								}
							}

							//Actualizo contadores
							borradores.printCounts();
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
	},

	show_eliminado: function(id){
		mydialog.show();
		mydialog.title('Cargando Post');
		mydialog.body('Cargando Post...', 200);
		mydialog.buttons(true, true, 'Aceptar', 'close', true, true, false);
		mydialog.center();
		mydialog.proccess_start();
        loading.start()
		$.ajax({
			type: 'POST',
			url: ZCodeApp.url + '/borradores-get.php',
			data: 'borrador_id=' + id,
			success: function(h){
				switch(h.charAt(0)){
					case '0': //Error
						mydialog.alert('Error', h.substring(3));
						break;
					case '1':
						mydialog.title('Post');
						mydialog.body(h.substring(3), 540);
						mydialog.buttons(true, true, 'Aceptar', 'close', true, true, false);
						mydialog.center();
						break;
				}
                loading.end()
			},
			error: function(){	
				mydialog.alert('Error', 'Hubo un error al intentar procesar lo solicitado');
                loading.end()
			},
			complete: function(){
				UPModal.proccess_end();
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
	borradores.template_borrador = $('#template-result-borrador').html();
	$('#template-result-borrador').remove();

	//Inicializo contadores
	borradores.counts = {'todos': 0, 'borradores':0, 'eliminados':0, 'categorias': {}};

	//Hago conteo inicial
	$.each(borradores_data, function(i, borrador){
		borradores.counts['todos']++;
		borradores.counts[borrador['tipo']]++;
		if(borradores.counts['categorias'][borrador['categoria']])
			borradores.counts['categorias'][borrador['categoria']]['count']++;
		else{
			borradores.counts['categorias'][borrador['categoria']] = {'name': borrador['categoria_name'], 'count':1};
		}
	});
	borradores.counts['categorias'] = sortObject(borradores.counts['categorias']);

	//Imprimo los contadores
	borradores.printCounts(true);

	//Query inicial
	borradores.query(true);
});