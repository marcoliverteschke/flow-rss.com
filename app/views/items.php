<?php

	if(isset($feed))
	{
		echo '<header><h1>Items for feed „' . $feed->title . '”</h1></header>';
	}

	foreach($items as $item)
	{
		echo '<article class="item ' . ($item->time_starred > 0 ? ' starred' : '') . ($item->time_read > 0 ? ' read' : '') . '" data-guid="' . $item->guid . '">';
			echo '<h1>' . $item->title . '</h1>';
			echo '<footer>' . date('d.m.Y H:i', $item->pubDate) . ' | ' . $item->feed->title . ' | <a class="pictogram" href="' . $item->link . '" target="_blank">o</a> | <a class="pictogram" data-tool="star" href="javascript:void(0);">*</a></footer>';
		echo '</article>';
	}
