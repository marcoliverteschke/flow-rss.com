<!doctype html>  
<html lang="en">  
	<head>  
		<meta charset="utf-8">  
		<title><?php if(isset($counter) && $counter > 0) { echo $counter . ' items | '; } ?>Flow RSS</title>  
		<meta name="viewport" content="width=device-width">
		<meta name="description" content="Flow RSS">
		<meta name="author" content="SitePoint">
		<link rel="stylesheet" href="/min/?f=/css/normalize.css,/css/styles.css">
		<!--[if lt IE 9]>  
			<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		
		<link rel="icon" href="/favicon.png" type="image/png" />
		<link rel="apple-touch-icon" type="image/x-icon" href="/apple-touch-icon.png"/>
	</head>  
	<body>
		<nav role="main">
			<ul>
				<li><a class="pictogram" href="/items/new" title="New items">{</a></li>
				<li><a class="pictogram" href="/items/starred" title="Starred items">*</a></li>
				<li><a class="pictogram" href="/feeds" title="Feeds">^</a></li>
			</ul>
		</nav>
		<div class="clear"></div>
		<?php echo $body_content ?>
		<script src="/min/?f=/js/jquery.js,/js/scripts.js"></script>
	</body>  
</html>  