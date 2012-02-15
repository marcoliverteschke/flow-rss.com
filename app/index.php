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
		$items = R::find('items', '1 ORDER BY pubDate DESC LIMIT 50');
		R::close();

		Flight::render('items', array('items' => $items), 'body_content');		
		Flight::render('layout');
	});
	
	Flight::start();