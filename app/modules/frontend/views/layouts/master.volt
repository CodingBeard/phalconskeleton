{# 
@package: phalconskeleton
@author: Tim Marshall <Tim@CodingBeard.com>
@copyright: (c) 2015, Tim Marshall
@license: New BSD License
#}<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
  <head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	{{ get_title() }}
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
	<link rel="apple-touch-icon" sizes="180x180" href="/img/icons/apple-touch-icon-180x180.png">
	<link rel="icon" type="image/png" href="/img/icons/android-chrome-192x192.png" sizes="192x192">
	<meta name="msapplication-square310x310logo" content="/img/icons/largetile.png" />
	<meta property="og:url" content="http://{{ _SERVER['SERVER_NAME'] ~ _SERVER['REQUEST_URI'] }}" />
	<meta property="og:title" content="{{ get_title()|striptags|trim }}" />
	<meta property="og:image" content="" />
	<link rel="stylesheet" href="{{ url(assets.collection('css').getTargetUri()) }}" />
	<script src="{{ url('js/modernizr-2.7.1.min.js') }}"></script>
	{% block head %}
	{% endblock %}
  </head>
  <body>
	<!--[if lt IE 7]>
		<p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
	<![endif]-->
	<div class="wrapper">
	  <header>
		{% block header %}

		{% endblock %}
	  </header>
	  <main>
		{% block content %}

		{% endblock %}
		<div class="push"></div>
	  </main>
	</div>
	{% block footer %}
	{% endblock %}
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
	<div id="cookiebanner"
		 data-bg="#a81826"
		 data-fg="#fff"
		 data-height="32px"
		 data-message="We use cookies to improve your browsing experience, by continuing to use this site you accept our use of cookies.">
	</div>
	<script src="{{ url(assets.collection('js').getTargetUri()) }}"></script>
	{% block javascripts %}
	{% endblock %}
	<script>
		(function (b, o, i, l, e, r) {
		  b.GoogleAnalyticsObject = l;
		  b[l] || (b[l] =
				  function () {
					(b[l].q = b[l].q || []).push(arguments)
				  });
		  b[l].l = +new Date;
		  e = o.createElement(i);
		  r = o.getElementsByTagName(i)[0];
		  e.src = '//www.google-analytics.com/analytics.js';
		  r.parentNode.insertBefore(e, r)
		}(window, document, 'script', 'ga'));
		ga('create', 'UA-XXXXX-X');
		ga('send', 'pageview');
	</script>
  </body>
</html>