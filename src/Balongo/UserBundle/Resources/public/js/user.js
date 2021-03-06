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
	
	$('#btnCont button').click(function(){
		$('.tab').removeClass('active');
		var toggle = $(this).data('toggle');
		$('div#'+toggle).addClass('active');
		
		$('#btnCont button.btn-primary').removeClass('btn-primary').addClass('btn-info');
		$(this).removeClass('btn-info').addClass('btn-primary');
	});
	
	$('div#pass form input[type="password"]').eq(1).bind('keyup', function(e){
		e.preventDefault();
		var s = $(this).val(),
			 e = $('#security-info span'),
			 c = 0;
		c += ( s.length >= 6 )		? 	1: 0;
		c += ( s.match(/[0-9]/) )	? 	1: 0;
		c += ( s.match(/[A-Z]/) )	? 	1: 0;
		c += ( s.match(/[a-z]/) )	? 	1: 0;
		c += ( s.match(/[\W]/) )	? 	1: 0;
		
		switch (c) {
			case 0: 	$(e).text('Nulo').css('color', '#d9534f'); 		break;
			case 1: 	$(e).text('Pobre').css('color', '#E45635'); 		break;
			case 2: 	$(e).text('Medio').css('color', '#EAC617'); 		break;
			case 3: 	$(e).text('Normal').css('color', '#009EE3'); 	break;
			case 4: 	$(e).text('Alto').css('color', '#1D70B7'); 		break;
			case 5: 	$(e).text('Muy alto').css('color', '#5cb85c'); 	break;
		}
	});
	
	$('div.tab form').submit(function(e){
		e.preventDefault();
		var data = $(this).serialize(),
		    action = $(this).data('action');
		ajaxRequest(data, action);
	});
	
	
	if ( $('tr.rowentity').length ) {
		$('tr.rowentity').each(function(index, value){
			var id = $(this).data('id');
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
			s = s.replace(/[^A-Za-z0-9áéíóúÁÉÍÓÚàèìòùÀÈÌÒÙñÑïÏöÖüÜçÇ\s\.@\/]/g, '');
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
};


function ajaxRequest(data, action) {
	$.ajax({
		url: action,
		type: 'POST',
		async: true,
		cache: false,
		dataType: 'json',
		data: data,
		success: function(json) {
			if ( json.status == 'success' ) {
				$('.alert-success')
					.text(json.mensaje)
					.slideDown(400)
					.delay(3000)
					.slideUp(400);
				if ( json.desconecta ) {
					window.setTimeout(function(){
						window.location.href = $('a#linkLogOut').attr('href');
					}, 3000);
				}
			} else {
				$('.alert-warning')
					.text(json.mensaje)
					.slideDown(400)
					.delay(3000)
					.slideUp(400);
			}
		},
		error: function(json) {
			$('.alert-warning')
				.text(json.mensaje)
				.slideDown(400)
				.delay(3000)
				.slideUp(400);
		}
	});
};
