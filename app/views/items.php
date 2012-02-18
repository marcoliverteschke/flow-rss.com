<?php

	if(isset($feed))
	{
		echo '<header><h1>Items for feed „' . $feed->title . '”</h1></header>';
	}

	foreach($items as $item)
	{
		echo '<article class="item ' . ($item->time_starred > 0 ? ' starred' : '') . ($item->time_read > 0 ? ' read' : '') . '" data-guid="' . $item->guid . '">';
			echo '<h1>' . $item->title . '</h1>';
			echo '<footer>';
				echo date('d.m.Y H:i', $item->pubDate);
				echo ' | ';
				echo '<a href="/feeds/' . $item->feed->id . '">' . $item->feed->title . '</a>';
				echo ' | ';
				echo '<a class="pictogram" href="' . $item->link . '" target="_blank" title="Open original article">o</a>';
				echo ' | ';
				echo '<a class="pictogram" data-tool="star" href="javascript:void(0);" title="Star item">*</a>';
//				echo ' | ';
//				echo '<a class="pictogram" data-tool="instapaper" href="javascript:void(0);" title="Send article to Instapaper">a</a>';
			echo '</footer>';
		echo '</article>';
	}
