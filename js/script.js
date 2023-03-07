(function ($) {
	$(document).ready(function(){
		$( ".he-year" ).each(function( index ) {
			const year = $( this ).text();
			const heYear = gematriya(parseInt(year), {limit: 3});
			$( this ).text(heYear);
		});
	});
})(jQuery);