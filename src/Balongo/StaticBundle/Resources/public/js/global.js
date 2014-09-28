$(document).ready(function(){
	
	resize();
	window.onresize = resize;

	$("ul.dropdown-menu").hover(function(){
		$(this).prev().unbind('blur');
	}, function(){
		$('ul.nav-menu a.dropdown-toggle').bind('blur', function(e){
			e.preventDefault();
			$(this).next().hide();
		});
	});
	
});

// MENU RESPONSIVE

$('div.responsive-menu button').bind('click', function(e){
	e.preventDefault();
	$('ul.nav-menu').stop().slideToggle();
});

$('ul.nav-menu a.dropdown-toggle').bind('blur', function(e){
	e.preventDefault();
	$(this).next().hide();
	shown = false;
});

var shown = false;
$('ul.nav-menu a.dropdown-toggle').bind('click', function(e){
	e.preventDefault();
	if (shown) {
	$(this).next().hide(); shown = false; $(this).blur();
	} else {
	$(this).next().show(); shown = true; }
});

function resize() {
	if (window.innerWidth > 767) {
		$('ul.nav-menu').css('display', 'block');
	} else {
		$('ul.nav-menu').css('display', 'none');
	}
	if ( $('div.carousel-container').length ) {
		var w = $('div.carousel-container').outerWidth();
		$('div.carousel-container').outerHeight(w/3);
	}
}
