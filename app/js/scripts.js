var clacked = null;

$(document).ready(function(){
	
	$('body').keypress(function(e){
		if($('.item').length > 0)
		{
			if(e.keyCode == 106)
			{
				if(clacked == null)
				{
					clack($('.item')[0]);
				} else {
					var next_index = $('.item').index($('.item.open')) + 1;
					if(next_index < $('.item').length)
					{
						clack($('.item')[next_index]);
					}
				}
			}
			if(e.keyCode == 107)
			{
				if(clacked != null)
				{
					var previous_index = $('.item').index($('.item.open')) - 1;
					if(previous_index >= 0)
					{
						clack($('.item')[previous_index]);
					}
				}
			}
			if(e.keyCode == 65)
			{
				read_all_visible_items();
			}
		}
	});
	
	$('article.item h1').click(function(){
		clack_title(this);
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


function clack_title(title)
{
	clack($(title).parent('.item'))
}


function clack(item)
{
	clacked = item;
	toggle_clacked();
	if(!$(clacked).hasClass('read'))
	{
		$(clacked).addClass('read');
	}
}


function toggle_clacked()
{
	$('article.item .body:visible').slideUp(function(){
		$(this).parent('.item').removeClass('open');
		$(this).remove();
	});
	
	if($(clacked).find('.body:visible').length == 0)
	{
		if(!$(clacked).hasClass('read'))
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
			clacked = null;
		});
	}
}


function read_all_visible_items()
{
	$('.item').each(function(i, e){
		if(!$(e).hasClass('read'))
		{
			$(e).addClass('read');
			$.get('/items/read/' + $(e).attr('data-guid'), function(data){});
		}
	});
}