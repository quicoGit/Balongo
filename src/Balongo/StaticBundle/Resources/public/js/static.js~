$(document).ready(function(){
	
	/**
	 * FUNCIONES INIT
	 */
	initIndex();
	initLogin();
	renderMap();
	
	if ( $('form#balongoContactForm').length ) {
		$('form#balongoContactForm').on('submit', function(e){
			e.preventDefault();
			ajaxFormRequest( $(this).serialize() );
		});
	}
	
});


/**
 * Centrar el formulario de login.
 */
function initLogin() {
	if ( $('div.login-form-container').length ) {
		var	total = window.innerHeight,
				body = $('html').outerHeight();
		if (total > body)
			$('div.login-form-container')
				.css('margin-top', (total-body)/2+'px')
				.css('margin-bottom', (total-body)/2+'px');
	}
}


/**
 * INIT Página de Inicio
 */
(function() {
	// OCULTAR TODOS LOS SLIDERS
	$('div.carousel-slide').hide();
})();
var slides, indexSlide = 1;
function initIndex() { // SE EJECUTA CUANDO LA PÁGINA ESTA CARGADA
	if ( $('div.carousel-container').length ) {
		
		$('div.carousel-slide:nth-child(1)').show();
		//ANIMAR PRIMER SLIDE
		$('#slide-loader').animate({width:"100%"},8000,"linear");
		$('div.carousel-slide:nth-child(1) .slide-item').each(function(index, value){
			$(this).animate({
				left : "300px",
				opacity: 0
			}, 0, "linear");
		});
		$('div.carousel-slide:nth-child(1) .slide-item').each(function(index, value){
			var del = 400*index;
			$(this).delay(del).animate({
				left : "0",
				opacity: 1
			}, 800, "swing");
		});
		
		// slides = numero de slides
		slides = $('div.carousel-slide').length;
		
		// INTERVALO CADA 8 segundos
		window.setInterval(function(){
			if ( indexSlide < slides) {
				$('div.carousel-slide:nth-child('+indexSlide+')').fadeOut(400);
				indexSlide++;
			} else {
				$('div.carousel-slide:nth-child('+indexSlide+')').fadeOut(400);
				indexSlide = 1;
			}
			$('#slide-loader').stop().css("width", "0%").animate({width:"100%"},8000,"linear");
			$('div.carousel-slide:nth-child('+indexSlide+') .slide-item').each(function(index, value){
				var dir = (indexSlide%2 == 0) ? "-300px" : "300px";
				$(this).animate({
					left : dir,
					opacity: 0
				}, 0, "linear");
			});
			$('div.carousel-slide:nth-child('+indexSlide+')').fadeIn(400);
			$('div.carousel-slide:nth-child('+indexSlide+') .slide-item').each(function(index, value){
			var del = 400*index;
			$(this).delay(del).animate({
				left : "0",
				opacity: 1
			}, 800, "swing");
		});
		}, 8000); // ENDINTERVAL
		
		
	} //ENDIF
}


function renderMap() {
	if ( $('#balongoMap').length ) {
		var myCenter = new google.maps.LatLng(38.5384295,-0.1200007);
	
		var mapProp = {
			scrollwheel: false,
			center:myCenter,
			zoom:15,
			disableDefaultUI: true,
			mapTypeId:google.maps.MapTypeId.ROADMAP
		};
		
		var map = new google.maps.Map(document.getElementById("balongoMap"), mapProp);
		
		var marker = new google.maps.Marker({
			position:myCenter,
			icon: 'http://www.balongo.eu/bundles/static/img/bounce.png',
			draggable: false,
			animation: google.maps.Animation.BOUNCE,
		});

		marker.setMap(map);
	}
}

function ajaxFormRequest(dataForm) {
	var urldata = $('form#balongoContactForm').attr('data-action');
	$('form#balongoContactForm input[type="submit"]').attr('disabled', 'disabled');
	$.ajax({
		url: urldata,
		type: 'POST',
		async: true,
		cache: false,
		dataType: 'json',
		data: dataForm,
		success: function(json) {
			if ( json.data != 'error' ) {
				$('form#balongoContactForm').trigger('reset');
				$('.alert-success').slideDown(400, function(){
					window.setTimeout(function(){
						$('.alert-success').slideUp(400);
						$('form#balongoContactForm input[type="submit"]').removeAttr('disabled');
					}, 4000);
				});
			} else {
				$('.alert-warning').slideDown(400, function(){
					window.setTimeout(function(){
						$('.alert-warning').slideUp(400);
						$('form#balongoContactForm input[type="submit"]').removeAttr('disabled');
					}, 4000);
				});
			}
		},
		error: function() {
			$('.alert-warning').slideDown(400, function(){
				window.setTimeout(function(){
					$('.alert-warning').slideUp(400);
					$('form#balongoContactForm input[type="submit"]').removeAttr('disabled');
				}, 4000);
			});
		}
	});
}
