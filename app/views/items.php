<?php

	foreach($items as $item)
	{
		echo '<article class="item" data-guid="' . $item->guid . '">';
			echo '<h1>' . $item->title . '</h1>';
			echo '<footer>' . date('d.m.Y H:i', $item->pubDate) . ' | ' . $item->feed->title . '</footer>';
		echo '</article>';
	}
