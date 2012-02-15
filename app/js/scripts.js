var clacked = null;

$(document).ready(function(){
	$('article.item').click(function(){
		clacked = this;
		toggle_clacked()
	});
});


function toggle_clacked()
{
	$('article.item .body:visible').slideUp(function(){
		$(this).remove();
	});
	
	if($(clacked).find('.body:visible').length == 0)
	{
		$.get('/items/fetch/' + $(clacked).attr('data-guid'), function(data){
			$(clacked).find('h1').after(data);
			$(clacked).find('.body').slideDown();
		});
	} else {
		$(clacked).find('.body').slideUp(function(){
			$(this).remove();
		});
	}
	
}