<?php

	foreach($feeds as $feed)
	{
		echo '<article class="feed" data-fid="' . $feed->id . '">';
			echo '<h1><a href="/feeds/' . $feed->id . '">' . $feed->title . '</a></h1>';
		echo '</article>';
	}
