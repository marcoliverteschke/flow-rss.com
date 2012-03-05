var clacked = null;
var items_to_read = null

$(document).ready(function(){
	
	$('.body a').live('mouseover', function(e){
		$(this).attr('target', '_blank');
	});
	
	$('.js-pjax').pjax('#content', {
		timeout: null,
		beforeSend: function(jqXHR, settings){
			toggle_loading();
		},
		error: function(xhr, err){
			$('.error').text('Something went wrong: ' + err)
		},
		success: function(data, textStatus, jqXHR) {
			toggle_loading();
		}
	});
	
	$(document).keypress(function(e){
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
			if(e.keyCode == 115 && clacked != null)
			{
				star_item(clacked);
			}
		}
	});
	
	$('article.item h1').live('click', function(){
		clack_title(this);
	});
	
	$('a[data-tool="star"]').live('click', function(){
		star_item($(this).parents('.item'));
	});
	
	$('a[data-tool="unsubscribe"]').live('click', function(){
		$.post('/feeds/unsubscribe', {'fid' : $(this).attr('data-fid')}, function(){
			window.location = '/feeds';
		});
	});
});


function star_item(item)
{
	if(item.hasClass('starred'))
	{
		$(item).removeClass('starred');
	} else {
		$(item).addClass('starred');
	}
	$.get('/items/star/' + $(item).attr('data-guid'), function(data){});
}


function clack_title(title)
{
	clack($(title).parent('.item'))
}


function clack(item)
{
	clacked = $(item);
	toggle_clacked();
	if(!clacked.hasClass('read'))
	{
		clacked.addClass('read');
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
		{
			$.get('/items/read/' + $(clacked).attr('data-guid'), function(data){});
			update_unread_count_in_title();
		}
		$.get('/items/fetch/' + $(clacked).attr('data-guid'), function(data){
			$(clacked).addClass('open');
			$(clacked).find('h1').after(data);
			$(clacked).find('.body').slideDown(function(){
				if($('[role="main"]').offset().top == 0)
				{
					$('html,body').scrollTop($(clacked).offset().top - $('[role="main"]').outerHeight());
				} else {
					$('html,body').scrollTop($(clacked).offset().top);
				}
			});
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
	items_to_read = new Array();
	$('.item:not(.read)').each(function(i, e){
		items_to_read[i] = $(e).attr('data-guid');
	});
	$.post('/items/read/', {'items_to_read' : items_to_read}, function(data){
		$('.item:not(.read)').addClass('read');
		update_unread_count_in_title();
	});
}


function toggle_loading()
{
	$('#loading').toggle();
}


function update_unread_count_in_title()
{
	$.get('/items/new/count', function(data){
		if(data.length > 0)
			$('title').replaceWith(data);
	});
}