var clacked = null;

$(document).ready(function(){
	$('article.item h1').click(function(){
		clacked = $(this).parent('.item');

		if(!$(clacked).hasClass('read'))
		{
			$(clacked).addClass('read');
		}

		toggle_clacked();
	});
	
	$('a[data-tool="star"]').click(function(){
		if($(this).parents('.item').hasClass('starred'))
		{
			$(this).parents('.item').removeClass('starred');
		} else {
			$(this).parents('.item').addClass('starred');
		}
		$.get('/items/star/' + $(this).parents('.item').attr('data-guid'), function(data){});
	});
});


function toggle_clacked()
{
	$('article.item .body:visible').slideUp(function(){
		$(this).parent('.item').removeClass('open');
		$(this).remove();
	});
	
	if($(clacked).find('.body:visible').length == 0)
	{
		$.get('/items/read/' + $(clacked).attr('data-guid'), function(data){});
		$.get('/items/fetch/' + $(clacked).attr('data-guid'), function(data){
			$(clacked).addClass('open');
			$(clacked).find('h1').after(data);
			$(clacked).find('.body').slideDown();
		});
	} else {
		$(clacked).find('.body').slideUp(function(){
			$(clacked).removeClass('open');
			$(this).remove();
		});
	}
}