<?php

	foreach($items as $item)
	{
		echo '<article class="item">';
			echo '<h1>' . $item->title . '</h1>';
			echo '<div class="body"></div>';
		echo '</article>';
	}
