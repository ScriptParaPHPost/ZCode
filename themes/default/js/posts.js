const favorito = {
	agregado: false,
	comprobar() {
		if(this.agregado || !gget('key')) {
			if(!gget('key')) UPModal.alert('Login', 'Tienes que estar logueado para realizar esta operaci&oacute;n');
			return;
		}
	},
	total() {
		const FAVTOTAL = $('span.favoritos_total');
		let total = parseInt(FAVTOTAL.data('total'));
		FAVTOTAL.html(UPAbbr(total + 1));
	},
	agregar(postid) {
		this.comprobar();
		loading.start();
		$.post(`${ZCodeApp.url}/favoritos-agregar.php`, { postid }, req => {
			let statusFavPost = (parseInt(req.charAt(0)) === 0);
			UPModal.alert((statusFavPost ? 'Error' : 'Bien'), req.substring(3));
			if(req.charAt(0) === '1') this.total();
			loading.end();
		})
		.fail(function() {
			favorito.agregado = false;
			UPModal.error_500("favorito.agregar()");
			loading.end();
		});
	}
}

const votar = {
	votado: false,
	comprobar(voto) {
		if(this.votado) return;
		if(voto == null || isNaN(voto) != false || voto < 1) {
			UPModal.alert('Error', 'Debe introducir n&uacute;meros');
			return false;
		}
		this.votado = true;
	},
	verificar(hide) {
		if(this.votado) return;
	},
	votar_post(voto_a_dar) {
		this.comprobar(voto_a_dar);
		loading.start();
		$.post(ZCodeApp.url + '/posts-votar.php', 'puntos=' + voto_a_dar + gget('postid'), req => {
			this.verificar(true);
			let votoAct = (parseInt(req.charAt(0)) === 1);
			let votoMsg = req.substring(3);
			UPModal.alert((votoAct ? 'Votado' : 'Error'), votoMsg, false)
			if(votoAct) {
				const total_votos = parseInt($('#puntos_post').html());
				const vp_total = parseInt($('#vp_total').html());
				let total_votos_suma = total_votos + parseInt(voto_a_dar);
				$('#puntos_post').html(number_format(total_votos_suma, 0, ',', '.'));
				$('#vp_pos, #vp_neg').remove();
				let total = vp_total + (voto_a_dar === 2 ? - 1 : + 1);
				$('#vp_total').html(total);
 				if(total < 0) $('#vp_total').css({ background: '#B92626' }); 
			}
			loading.end();
		}).fail(() => {
			this.votado = false;
			UPModal.error_500("votar.votar_post('"+voto_a_dar+"')");
			loading.end();
		})
	}
}


/* Borrar Post */
function borrar_post(aceptar) {
	UPModal.setModal({
		title: 'Borrar Post',
		body: (!aceptar ? '&#191;Quiere eliminar este post?' : '&iquest;Seguro que deseas borrar este post?'),
		buttons: {
			confirmTxt: 'S&iacute;, borrar',
			confirmAction: `borrar_post(`+ (!aceptar ? 1 : 2) +`)`,
			cancelShow: true
		}
	});
	if(!aceptar || aceptar === 1) return;
	UPModal.proccess_start('Eliminando...');
	loading.start();
	$.post(ZCodeApp.url + '/posts-borrar.php', gget('postid', true), req => {
		var title = (req.charAt(0) == '0') ? 'Error' : 'Post Borrado';
		UPModal.alert(title, req.substring(3), (req.charAt(0) == '1'));
		loading.end();
	}).done(() => {
		UPModal.proccess_end();
		loading.end();
	}).fail(() => {
		UPModal.error_500("borrar_post(2)");
		loading.end();
	})
}

const comentario = {
	cache: {},
	cargando: false,
	lengthMax: 500,
	totalComments() {
		return parseInt($('#ncomments').text());
	},
	setPagePHP(page, paramGet = '') {
		const { url } = ZCodeApp;
		let isParam = empty(paramGet) ? '' : `?${paramGet}`;
		return `${ZCodeApp.url}/${page}.php` + isParam;
	},
	borrar(comid, autor, postid, status = false) {
		imported('posts/comentario-borrar.js', 'handleDeleteComment', { comid, autor, postid, status });
	},
	ocultar(comid, autor) {
		imported('posts/comentarios.js', 'handleHideComment', { comid, autor });
	},
	reaccionar(cid) {
		$('.reaccion#' + cid).toggleClass('d-none d-flex');
	},
	reaccion(cid, reaccion) {
		imported('posts/reaccionar.js', 'handleReactionComment', { cid, reaccion });
	},
	responser(cid) {
		$('#boxComentar' + cid).toggle();
	},
	nuevo() {
		imported('posts/comentario-nuevo.js', 'handleCommentAndReply', {});
	},
	responser_comentario(cid) {
		imported('posts/comentario-nuevo.js', 'handleCommentAndReply', { cid });
	},
	editar(cid, gew = false) {
		imported('posts/comentario-editar.js', 'handleCommentEdit', { cid, gew });
	},
	cargar(postid, page, autor) {
		// GIF
		$('#load_comments').show();
		$('#comentarios').css('opacity', 0.4);
		// COMPROBAMOS CACHE
		let cache = `c_${page}`;
		if(typeof comentario.cache[cache] == 'undefined') {
			loading.start()                                     
			$.post(this.setPagePHP('comentario-ajax', `page=${page}`), { postid, autor }, response => {
				comentario.cache[cache] = response;
				comentario.setPages({ postid, page, autor });
				$('#comentarios').html(response);
				loading.end()
			});
		} else {
			$('#comentarios').html(comentario.cache[cache]).css('opacity', 1);
			$('.paginadorCom').html(comentario.cache[`p_${page}`]);
			$('#load_comments').hide();
		}
	},
	setPages({ postid, page, autor }) {
		loading.start();
		let total = comentario.totalComments();
		$.post(this.setPagePHP('comentario-pages', `page=${page}`), { postid, autor, total }, response => {
			comentario.cache[`p_${page}`] = h;
			$('.paginadorCom').html(h);
			$('#load_comments').hide();
			$('#comentarios').css('opacity', 1);
			loading.end();
		});
	}
}

/* BBCode */
function spoiler(obj){
	$(obj).toggleClass('show').parent().next().slideToggle();
}

const compartirPost = {
	facebook: 'https://www.facebook.com/sharer/sharer.php?u=$1',
	twitter: 'https://twitter.com/intent/tweet?url=$1&text=$2',
	telegram: 'https://t.me/share/url?url=$1&text=$2',
	whatsapp: {
		mobile: 'whatsapp://send?text=$2 - $1',
		desktop: 'https://wa.me/?text=$2 - $1'
	},
	texto: 'Hola a todos, los invito a ver este articulo espectacular!',
	reemplazar({ url, shared }) {
		let invite = (shared == 'telegram') ? rawurlencode(this.texto) : encodeURIComponent(this.texto);
		let newText;
      if (shared === 'whatsapp') {
         const isMobile = /Mobi|Android/i.test(navigator.userAgent);
         newText = isMobile ? this.whatsapp.mobile : this.whatsapp.desktop;
      } else {
         newText = this[shared];
      }
      url += '?in=' + shared
      newText = newText.replace('$1', encodeURIComponent(url)).replace('$2', invite);
      return newText;
	},
	initialize() {
		const { postid } = ZCodeApp;
		const url = $('.compartir').data('url');
		const title = $('.compartir').data('title');
		$('[data-social]').each(function() {
			const typeSocial = $(this);
			typeSocial.on('click', function() {
				const shared = typeSocial.data('social');
				if(shared === 'web') {
					notifica.share('post', postid)
				} else {
					let newUrl = compartirPost.reemplazar({ url, shared });
					window.open(newUrl, title, 'directories=no, location=no, menubar=no, scrollbars=yes, statusbar=no, tittlebar=yes, width=700, height=400, left=300, top=150');
				}
			})
		})
	}
}

$(document).ready(() => {
	compartirPost.initialize();
	//Editor de posts comentarios
	if( $('.boxResponder').length ) {
		$('.boxResponder').css({ height: 40 }).html('').wysibb({ buttons: "smilebox,|,bold,italic,underline,strike" });
	}
	if( $('#boxComentar').length ) {
		$('#boxComentar').css({ height: 80 }).html('').wysibb({ buttons: "smilebox,|,bold,italic,underline,strike,img,link" })
	}
	let openDropdown = true;
	$('svg[role="button"]').on('click', function (argument) {
		const cid = $(this).attr('cid');
		$(`.dropdown-options`).removeClass('show');
		$(`.dropdown-options[dropdown="${cid}"]`)[(openDropdown ? 'addClass' : 'removeClass')]('show');
		openDropdown = !openDropdown;
	});
	$('#box_post').on('click', function() {
		$(this).parent().find('.box_post').toggle();
	})
	//comentario.cargar(loadComments.post_id, 1, loadComments.autor);

   const anchor = window.location.hash;
   if (anchor) {
      const element = $(anchor);
      if (element) {
         element.addClass("highlight");
         setTimeout(() => {
            element.removeClass("highlight");
         }, 3000);
      }
   }
});