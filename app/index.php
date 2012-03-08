<?php
	
	require('../lib/flight/Flight.php');
	require('../lib/rb.php');
	require('../lib/lessc.inc.php');

	Flight::before('start', function(&$params, &$output){
		lessc::ccompile('css/styles.less', 'css/styles.css');
		$request = Flight::request();

		if($request->url == '/login' && $request->method == 'POST')
		{
			if(isset($request->data['username']) && $request->data['username'] == 'marcoliver' && isset($request->data['password']) && md5($request->data['password']) == '4afa8481af06005ceb3801675c3cddac')
			{
				setcookie('flow_authenticated', 'true', time()+60*60*24*30, '/');
				Flight::redirect('/');
			}
		}

		if($request->url != '/login')
		{
			if(isset($request->cookies['flow_authenticated']) && $request->cookies['flow_authenticated'] == 'true')
			{
				Flight::set('authenticated', true);
			} else {
				Flight::set('authenticated', false);
				Flight::redirect('/login');
			}
		}
		
		Flight::set('ajax', false);
		if(isset($request->query['_pjax']) && $request->query['_pjax'])
		{
			Flight::set('ajax', true);
		}
		
		
		if($_SERVER['SERVER_ADDR'] == '87.106.88.22')
		{
			R::setup('mysql:host=localhost;dbname=flowrss', 'flowrss', 'oGhaiW0h');
		} else {
			R::setup('mysql:host=localhost;dbname=flowrss', 'root', 'root');
		}
		R::freeze(true);
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

	Flight::route('/login', function(){
		Flight::render('login', null, 'body_content');
		Flight::render('layout');
	});
	
	Flight::route('/feeds', function(){
		$feeds = R::find('feed', '1 ORDER BY title ASC');
		Flight::render('feeds', array('feeds' => $feeds), 'body_content');
		if(Flight::get('ajax') == true)
		{
			Flight::render('blank');
		} else {
			Flight::render('layout');
		}
	});

	Flight::route('/feeds/unsubscribe', function(){
		if(Flight::request()->method == 'POST')
		{
			R::exec('DELETE FROM item WHERE feed_id = ?', array(Flight::request()->data['fid']));
			R::exec('DELETE FROM feed WHERE id = ?', array(Flight::request()->data['fid']));
		}
	});

	Flight::route('/feeds/subscribe', function(){
//		if(Flight::request()->method == 'POST')
//		{
//			R::exec('DELETE FROM item WHERE feed_id = ?', array(Flight::request()->data['fid']));
//			R::exec('DELETE FROM feed WHERE id = ?', array(Flight::request()->data['fid']));
//		}
		Flight::render('add_feed', array(), 'body_content');
		if(Flight::get('ajax') == true)
		{
			Flight::render('blank');
		} else {
			Flight::render('layout');
		}
	});

	Flight::route('/feeds/@id', function($id){
		$feed = R::findOne('feed', 'id = ?', array($id));
		$items = R::find('item', 'feed_id = ? ORDER BY pubDate DESC', array($id));
		Flight::render('items', array('feed' => $feed, 'items' => $items), 'body_content');
		if(Flight::get('ajax') == true)
		{
			Flight::render('blank');
		} else {
			Flight::render('layout');
		}
	});
	
	Flight::route('/items', function(){
	    Flight::redirect('/items/new');
	});
	
	Flight::route('/items/new', function(){
		$items = R::find('item', 'time_read = 0 ORDER BY pubDate ASC LIMIT 100');
		$items_count = R::getCell('SELECT count(*) FROM item WHERE time_read = 0');
		foreach($items as $item)
		{
			// iterate over all items and prompt RedBean to load the parent feed
			$item->feed;
		}
		Flight::render('items', array('items' => $items, 'theres_more' => ($items_count > count($items))), 'body_content');		
		$title_prefix = ($items_count > 0 ? $items_count . ' items | ' : '');
		if(Flight::get('ajax') == true)
		{
			Flight::render('blank', array('title_prefix' => $title_prefix));
		} else {
			Flight::render('layout', array('title_prefix' => $title_prefix));
		}
	});
	
	Flight::route('/items/new/count', function(){
		$items_count = R::getCell('SELECT count(*) FROM item WHERE time_read = 0');
		$title_prefix = ($items_count > 0 ? $items_count . ' items | ' : '');
		Flight::render('blank', array('title_prefix' => $title_prefix));
	});
	
	
	Flight::route('/items/starred', function(){
		$items = R::find('item', 'time_starred != 0 ORDER BY time_starred DESC');
		foreach($items as $item)
		{
			// iterate over all items and prompt RedBean to load the parent feed
			$item->feed;
		}
		Flight::render('items', array('items' => $items), 'body_content');		
		if(Flight::get('ajax') == true)
		{
			Flight::render('blank');
		} else {
			Flight::render('layout');
		}
	});
	
	Flight::route('/items/fetch/@guid', function($guid){
		$item = R::findOne('item', 'guid = ?', array($guid));
		
		if(!empty($item))
		{
			Flight::render('item_details', array('item' => $item));
		}
	});
	
	Flight::route('/items/read/', function($guid){
		$items_to_read = R::find('item',' guid IN (' . R::genSlots(Flight::request()->data['items_to_read']) . ') ', Flight::request()->data['items_to_read']);
		
		foreach($items_to_read as $item_to_read)
		{
			if($item_to_read->time_read == 0)
			{
				$item_to_read->time_read = time();
			} else {
				$item_to_read->time_read = 0;
			}
			R::store($item_to_read);			
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