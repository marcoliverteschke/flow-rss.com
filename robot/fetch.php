<?php
	
	require_once('../lib/rb.php');
	require_once('SimplePie.php');
	R::setup('mysql:host=localhost;dbname=flowrss', 'root', 'root');
	echo "DB connection established\n";
	$feeds = R::find('feed');
		
	
	$pie = new SimplePie();
	$pie->enable_cache(false);

	foreach($feeds as $feed)
	{
		$feed->last_fetch_started = time();
//		print_r($feed);
  		R::store($feed);		
		$pie->set_feed_url($feed->link);
		$pie->init();
		$feed->title = $pie->get_title();
		$pietems = $pie->get_items();
		
		foreach($pietems as $pietem)
		{
			$exists = R::findOne('item', 'guid = ?', array($pietem->get_id(true)));
			if(count($exists) == 0)
			{
				$author = $pietem->get_author();
				$item = null;
				$item = R::dispense('item');
				$item->feed_id = $feed->id;
				if(!empty($author) && $author->get_name() != '')
					$item->author = $author->get_name();
	//			$item->category = 
	//			$item->comments = 
				$item->description = $pietem->get_content();
	//			$item->enclosure = 
				$item->guid = $pietem->get_id(true);
				$item->hash	= $pietem->get_id(true);
				$item->link = $pietem->get_permalink();
				$item->pubDate = $pietem->get_date('U');
				if(empty($item->pubDate))
				{
					$item->pubDate = time();
				}
	//			$item->source = $pietem->get_source();
				$item->title = $pietem->get_title();
				$item->added = time();
				R::store($item);
			}
		}
		
//		print_r($items);
		$feed->last_fetch_finished = time();
		R::store($feed);
	}
	
	R::close();
	echo "DB closed\n";
