<?php
	
	require('../lib/flight/Flight.php');
	require('../lib/rb.php');
	require('../lib/lessc.inc.php');

	Flight::before('start', function(&$params, &$output){
		lessc::ccompile('css/styles.less', 'css/styles.css');
		
		if($_SERVER['SERVER_ADDR'] == '87.106.88.22')
		{
			R::setup('mysql:host=localhost;dbname=flowrss', 'flowrss', 'oGhaiW0h');
		} else {
			R::setup('mysql:host=localhost;dbname=flowrss', 'root', 'root');
		}
	});

	Flight::after('start', function(&$params, &$output){
		R::close();
	});

	/*
		/ => R::items/new
		feeds => all feeds
		feeds/@id => single feed, items scrollfetched
		items => R::items/new
		items/new => unread items chronological
		items/marked => marked items
	*/
	Flight::route('/', function(){
	    Flight::redirect('/items');
	});
	
	Flight::route('/feeds', function(){
		$feeds = R::find('feed', '1 ORDER BY title ASC');
		Flight::render('feeds', array('feeds' => $feeds), 'body_content');		
		Flight::render('layout');
	});

	Flight::route('/feeds/@id', function($id){
		$feed = R::findOne('feed', 'id = ?', array($id));
		$items = R::find('item', 'feed_id = ? ORDER BY pubDate DESC', array($id));
		Flight::render('items', array('feed' => $feed, 'items' => $items), 'body_content');
		Flight::render('layout');
	});

	Flight::route('/items', function(){
	    Flight::redirect('/items/new');
	});
	
	Flight::route('/items/new', function(){
		$items = R::find('item', 'time_read = 0 ORDER BY pubDate ASC');
		foreach($items as $item)
		{
			// iterate over all items and prompt RedBean to load the parent feed
			$item->feed;
		}
		Flight::render('items', array('items' => $items), 'body_content');		
		Flight::render('layout', array('counter' => count($items)));
	});
	
	Flight::route('/items/starred', function(){
		$items = R::find('item', 'time_starred != 0 ORDER BY time_starred DESC');
		foreach($items as $item)
		{
			// iterate over all items and prompt RedBean to load the parent feed
			$item->feed;
		}
		Flight::render('items', array('items' => $items), 'body_content');		
		Flight::render('layout');
	});
	
	Flight::route('/items/fetch/@guid', function($guid){
		$item = R::findOne('item', 'guid = ?', array($guid));
		
		if(!empty($item))
		{
			Flight::render('item_details', array('item' => $item));
		}
	});
	
	Flight::route('/items/read/@guid', function($guid){
		$item = R::findOne('item', 'guid = ?', array($guid));
		if($item->time_read == 0)
		{
			$item->time_read = time();
		} else {
			$item->time_read = 0;
		}
		R::store($item);
	});

	Flight::route('/items/star/@guid', function($guid){
		$item = R::findOne('item', 'guid = ?', array($guid));
		if($item->time_starred == 0)
		{
			$item->time_starred = time();
		} else {
			$item->time_starred = 0;
		}
		R::store($item);
	});
	
	Flight::start();
	
?>