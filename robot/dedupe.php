<?php
	
	require_once('../lib/rb.php');

	if(isset($argv[1]) && $argv[1] == 'production')
		R::setup('mysql:host=localhost;dbname=flowrss', 'flowrss', 'oGhaiW0h');
	else
		R::setup('mysql:host=localhost;dbname=flowrss', 'root', 'root');

	echo "DB connection established\n";

	// clean up duplicates
	$dupes = R::getAll('SELECT guid, count(*) AS dupes FROM item GROUP BY guid HAVING dupes > 1');
	
	foreach($dupes as $dupe)
	{
		$items = R::find('item', ' guid = ? ', array($dupe['guid']));
		$items_keys = array_keys($items);
		$keep = $items_keys[0];
		foreach($items as $key => $item)
		{
			if($key != $keep)
			{
				R::trash($item);
			}
		}
	}

	R::close();
	echo "DB closed\n";
