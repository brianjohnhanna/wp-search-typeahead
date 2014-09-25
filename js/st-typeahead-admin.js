jQuery(document).ready(function($){
	$('.all-pages input[type="checkbox"]').change( function(){
		if (this.checked) {
			console.log(this);
			$(this).parent().appendTo('.selected-pages');
		}
		else {
			$(this).parent().appendTo('.all-pages');
		}
	});
});