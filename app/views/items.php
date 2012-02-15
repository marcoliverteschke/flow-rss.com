<?php

	foreach($items as $item)
	{
		echo '<article class="item" data-guid="' . $item->guid . '">';
			echo '<h1>' . $item->title . '</h1>';
		echo '</article>';
	}
