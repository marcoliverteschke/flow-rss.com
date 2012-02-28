<!doctype html>  
<html lang="en">  
	<head>  
		<meta charset="utf-8">  
		<title><?php if(isset($title_prefix)) { echo $title_prefix; } ?>Flow RSS</title>  
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
				<li class="mobile-only"><a class="pictogram" href="javascript:read_all_visible_items();" title="Mark all items as read">%</a></li>
				<li><a class="pictogram js-pjax" href="/items/new" title="New items">{</a></li>
				<li><a class="pictogram js-pjax" href="/items/starred" title="Starred items">*</a></li>
				<li><a class="pictogram js-pjax" href="/feeds" title="Feeds">^</a></li>
			</ul>
		</nav>
		<div class="clear"></div>
		<div id="content"><?php echo $body_content ?></div>
		<div id="loading"></div>
		<script type="text/javascript">
			// Add a script element as a child of the body
			function downloadJSAtOnload() {
				var element = document.createElement("script");
				element.src = "/min/?f=/js/jquery.js,/js/jquery.pjax.js,/js/scripts.js";
				document.body.appendChild(element);
			}
			// Check for browser support of event handling capability
			if (window.addEventListener)
				window.addEventListener("load", downloadJSAtOnload, false);
			else if (window.attachEvent)
				window.attachEvent("onload", downloadJSAtOnload);
			else window.onload = downloadJSAtOnload;
		</script>
	</body>  
</html>  