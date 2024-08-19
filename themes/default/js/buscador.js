const buscador = {
   tipo: '{if !$tsEngine}web{$tsEngine}{/if}',
   input: {
      query: $('input[type="search"][name="query"]'),
      engine: $('input[type="radio"][name="engine"]'),
      autor: $('input[type="text"][name="autor"]'),
      category: $('select[name="category"]')
   },
   values: {
   	web: 'Buscar en Web!',
   	tags: 'Buscar en Tags!',
   	google: 'Buscar en Google!'
   },
   obtener(type) {
      return this.input[type].val();
   },
   seleccionado(tipo) {
	  	if(this.tipo == tipo) return;
		this.input.query.attr({ placeholder: this.values[tipo] });
	
		this.tipo = tipo;
		if(tipo === 'google') {
			let consulta = this.obtener('query') || this.obtener('autor');
			$('form[name="buscador"]').append(`<input type="hidden" name="gsc.q" value="${consulta}">`);
		}
	},
	clicSearch() {
      let form = $('form[name="buscador"]');
      let query = this.obtener('query');
      let autor = this.obtener('autor');

      if(this.tipo === 'google') {
         let newUrl = updateQueryStringParameter(form.attr('action'), 'engine', 'google');
         newUrl = updateQueryStringParameter(newUrl, 'query', query);
         newUrl = updateQueryStringParameter(newUrl, 'autor', autor);
         newUrl = updateQueryStringParameter(newUrl, 'category', this.obtener('category'));
         newUrl += `#gsc.q=${query || autor}&gsc.tab=0`;
         window.location.href = newUrl;
      } else {
         form.submit();
      }
   }
}

function checkSelection() {
   $('.up-searcher--tabs .tab-item').each(function(i, element) {
      let itemType = $(element).data('tipo');
      let itemChecked = $(element).find('input').prop('checked');
      if(itemChecked) {
         buscador.seleccionado(itemType);
      }
   });
}

function getParameterByName(name, url = window.location.href) {
   name = name.replace(/[\[\]]/g, '\\$&');
   let regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)');
   let results = regex.exec(url);
   if (!results) return null;
   if (!results[2]) return '';
   return decodeURIComponent(results[2].replace(/\+/g, ' '));
}

function updateQueryStringParameter(uri, key, value) {
   let re = new RegExp("([?&])" + key + "=.*?(&|#|$)(.*)", "gi");
   let hash;
   if (re.test(uri)) {
      if (typeof value !== 'undefined' && value !== null) return uri.replace(re, '$1' + key + "=" + value + '$2$3');
      else {
         hash = uri.split('#');
         uri = hash[0].replace(re, '$1$3').replace(/(&|\?)$/, '');
         if (typeof hash[1] !== 'undefined' && hash[1] !== null) uri += '#' + hash[1];
         return uri;
      }
   } else {
      if (typeof value !== 'undefined' && value !== null) {
         let separator = uri.indexOf('?') !== -1 ? '&' : '?';
         hash = uri.split('#');
         uri = hash[0] + separator + key + '=' + value;
         if (typeof hash[1] !== 'undefined' && hash[1] !== null)  uri += '#' + hash[1];
         return uri;
      } else return uri;
   }
}

$(document).ready(function() {

	// Verificar la selección actual al cargar la página
   checkSelection();

   // Añadir evento change a los inputs de radio
   $('.up-searcher--tabs .tab-item input[type="radio"]').change(() => checkSelection());

   $('.up-searcher [type="submit"]').on('click', (e) => {
      e.preventDefault();
      buscador.clicSearch()
   })
});