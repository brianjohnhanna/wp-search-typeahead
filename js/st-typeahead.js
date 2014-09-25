jQuery(document).ready(function($){
	var states = pages;
	// constructs the suggestion engine
	 states = new Bloodhound({
	  datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
	  queryTokenizer: Bloodhound.tokenizers.whitespace,
	  // `states` is an array of state names defined in "The Basics"
	  local: states
	});
	 
	// kicks off the loading/processing of `local` and `prefetch`
	states.initialize();
	 
	$('#bloodhound .typeahead').typeahead({
	  hint: true,
	  highlight: true,
	  minLength: 1
	},
	{
	  name: 'states',
	  displayKey: 'value',
	  source: states.ttAdapter(),
	  templates: {
	  	empty: [
	  		'<div class="tt-suggestion catalog-search">Search Catalog for <span class="suggestion"></span></div>',
	  		'<div class="tt-suggestion site-search">Search Site for <span class="suggestion"></span></div>'
	  	].join('\n'),
	  }
	});

	$('.typeahead').bind('typeahead:selected', function(obj, datum, name){
		
		if (datum !== undefined) {
			window.location.href = datum.url;
		}
		else {
			var query = $('.suggestion').html();
			
			if ( $('.tt-cursor').hasClass('site-search') ){
				window.location.href = '/?s=' + query;
			}
			else {
				window.location.href = 'http://catalog.mbln.org/Polaris/search/searchresults.aspx?ctx=71.1033.0.0.6&type=Keyword&term='+query+'&by=KW&sort=RELEVANCE&limit=TOM=*&query=&page=0&searchid=1';
			}
		}
	});

	$('#bloodhound .typeahead').keyup(function(){
		var suggestion = $('.typeahead').typeahead('val');
		window.setTimeout($('.suggestion').html(suggestion), 0);
	});

});