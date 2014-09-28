$(document).ready(function(){
	
	/**
	 * FUNCIONES INIT
	 */
	initPag();
	
	
	/**
	 * HEREDA LAS FUNCIONES DE GLOBAL EN ONRESIZE
	 */
	window.onresize = function() {
		initPag();
		resize();
	};
	
	if ( $('form.twigform').length ) {
		if ( $('textarea').length ) $('textarea').css('resize', 'vertical');
		$('form.twigform').children().filter('div').each(function(){
			$(this).addClass('form-group');
		});
		$('form.twigform').children().filter('div').last().addClass('text-right');
	}
	
	
	
	/**
	 * Tachar las entidades al seleccionar el checkbox de eliminar
	 */
	$('tr.rowuser input[type="checkbox"]').each(function(index, value){
		if ( $(this).is(':checked') ) {
			$(this).parent().parent().css('text-decoration', 'line-through').css('color', '#AAA');
		}
	});
	
	
	$('body').on( 'click', 'tr.rowentity input[type="checkbox"]', function(e){
		if ( $(this).is(':checked') ) {
			$(this).parent().parent().css('text-decoration', 'line-through').css('color', '#AAA');
		} else {
			$(this).parent().parent().css('text-decoration', 'none').css('color', '#333');
		}
	});
	
	/**
	 * Envio por AJAX del formulario para eliminar entidades
	 */
	if ( $('.form_delete_entity').length ) {
		$('.form_delete_entity').on('submit', function(e){
			e.preventDefault();
			$('#modalbutton-close').click();
			ajaxFormDeleteEntityRequest( $(this).serialize() );
		});
	}
	
	/**
	 * Filtrado de entidades al teclear en el campo de filtro
	 */
	if ( $('tr.rowentity').length ) {
		$('tr.rowentity td').not('.text-right').not('.hasLink').bind('click', function(){
			var c = $(this).parent().children().last().children().filter('input');
			if ( $(c).is(':checked') ) $(c).click();
			else $(c).click();
		});
		$('tr.rowentity').each(function(index, value){
			var id = $(this).children().last().children().filter('input').attr('value');
			var text = '';
			$(this).children().each(function(i, v){
				text += $(this).text()+' ';
			});
			entities[index] = new Entity(id, text);
		});
	}
	
	if ( $('#filtro').length ) {
		$('#filtro').val('');
		$('#filtro').bind('keyup', function(e){
			e.preventDefault();
			var s = $(this).val();
			s = s.replace(/[^A-Za-z0-9áéíóúÁÉÍÓÚàèìòùÀÈÌÒÙñÑïÏöÖüÜçÇ\s\.@]/g, '');
			s = s.replace(/^\s+|\s+$/g, '');
			s = s.split(/\s+/);
			
			for ( var x = 0; x < entities.length; x++ ) {
				for ( var y = 0; y < s.length; y++ ) {
					var regex = new RegExp(s[y], 'i');
					if ( entities[x].text.match(regex) ) {
						entities[x].mostrado = true;
					} else {
						entities[x].mostrado = false;
						break;
					}
				}
			}
			
			for ( var x = 0; x < entities.length; x++ ) {
				if ( entities[x].mostrado ) {
					$('tr#e'+entities[x].id).show();
				} else {
					$('tr#e'+entities[x].id).hide();
				}
			}
			
			var todosHide = true;
			for ( var x = 0; x < entities.length; x++ ) {
				if ( entities[x].mostrado ) {
					todosHide = false;
					break;
				}
			}
			
			if ( todosHide ) {
				$('#rownotfound').show();
			} else {
				$('#rownotfound').hide();
			}
			
			initPag();
		});
	}
	
});

// CLASE ENTITY PARA EL FILTRADO DE ENTIDADES
var entities = new Array();
function Entity(id, text) {
	this.id = id;
	this.text = text;
	this.mostrado = true;
}


// VENTANA MODAL R_Entity
(function(){
	if ( $('div.modal').length ) {
		
		$('#modalbutton').bind('click', function(e){
			e.preventDefault();
			var ele = $(this).attr('data-target');
			$(ele).children().animate({top: '30px'},400,'swing');
			$(ele).fadeIn(400);
		});
		
		$('#modalbutton-close').bind('click', function(e){
			e.preventDefault();
			var ele = $(this).attr('data-target');
			$(ele).children().animate({top: '-200px'},400,'swing');
			$(ele).fadeOut(400);
		});
		
	}
})();

/**
 * Alargar página si no llega hasta el final.
 */
function initPag() {
	$('div.container').css('height', 'inherit');
	var	total = window.innerHeight,
			body = $('html').outerHeight();
	if (total > body) {
		height = $('div.container').outerHeight();
		$('div.container').outerHeight(height+(total-body));
	}
}



function ajaxFormDeleteEntityRequest(dataForm) {
	var urldata = $('form.form_delete_entity').attr('data-action');
	$.ajax({
		url: urldata,
		type: 'POST',
		async: true,
		cache: false,
		dataType: 'json',
		data: dataForm,
		success: function(json) {
			if ( json.data != 'error' ) {
				$('form.form_delete_entity input[type="checkbox"]').each(function(index, value){
					if ( $(this).is(':checked') ) {
						$(this).parent().parent().remove();
					}
				});
				$('.alert-success').slideDown(400, function(){
					window.setTimeout(function(){
						$('.alert-success').slideUp(400);
					}, 4000);
				});
			} else {
				$('.alert-warning').slideDown(400, function(){
					window.setTimeout(function(){
						$('.alert-warning').slideUp(400);
					}, 4000);
				});
			}
		},
		error: function() {
			$('.alert-warning').slideDown(400, function(){
				window.setTimeout(function(){
					$('.alert-warning').slideUp(400);
				}, 4000);
			});
		}
	});
};
