var clacked = null;

$(document).ready(function(){
	$('article.item').click(function(){
		clacked = this;
		$.get('/items/fetch/' + $(this).attr('data-guid'), function(data){
			console.log(clacked);
			$(clacked).find('h1').after(data);
		});
	});
});