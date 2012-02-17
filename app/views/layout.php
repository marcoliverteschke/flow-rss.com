<!doctype html>  
<html lang="en">  
	<head>  
		<meta charset="utf-8">  
		<title><?php if(isset($counter) && $counter > 0) { echo $counter . ' items | '; } ?>Flow RSS</title>  
		<meta name="viewport" content="width=device-width">
		<meta name="description" content="Flow RSS">
		<meta name="author" content="SitePoint">
		<link rel="stylesheet" href="/css/normalize.css?v=1.0">
		<link rel="stylesheet" href="/css/styles.css?v=1.0">
		<!--[if lt IE 9]>  
			<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->  
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
		<script src="/js/jquery.js"></script>  
		<script src="/js/scripts.js"></script>  
	</body>  
</html>  