<?php
	
	require('../lib/flight/Flight.php');
	require('../lib/rb.php');
	require('../lib/lessc.inc.php');

	Flight::before('start', function(&$params, &$output){
		lessc::ccompile('css/styles.less', 'css/styles.css');
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

	Flight::route('/items', function(){
	    Flight::redirect('/items/new');
	});
	
	Flight::route('/items/new', function(){
		R::setup('mysql:host=localhost;dbname=flowrss', 'root', 'root');
		$items = R::find('items', 'time_read = 0 ORDER BY pubDate DESC LIMIT 50');
		R::close();

		Flight::render('items', array('items' => $items), 'body_content');		
		Flight::render('layout');
	});
	
	Flight::route('/items/fetch/@guid', function($guid){
		R::setup('mysql:host=localhost;dbname=flowrss', 'root', 'root');
		$item = R::findOne('items', 'guid = ?', array($guid));
		R::close();
		
		if(!empty($item))
		{
			Flight::render('item_details', array('item' => $item));
		}
	});
	
	Flight::route('/items/read/@guid', function($guid){
		R::setup('mysql:host=localhost;dbname=flowrss', 'root', 'root');
		$item = R::findOne('items', 'guid = ?', array($guid));
		if($item->time_read == 0)
		{
			$item->time_read = time();
		} else {
			$item->time_read = 0;
		}
		error_log(print_r($item, 1));
		R::store($item);
		R::close();
	});
	
	Flight::start();