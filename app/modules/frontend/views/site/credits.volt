{# 
phalconskeleton
author Tim Marshall
copyright (c) 2015, Tim Marshall
#}
{% extends 'layouts/master.volt' %}

{% block head %}

{% endblock %}

{% block header %}
	{% include "layouts/header.volt" %}
	{% include "layouts/navigation.volt" %}
{% endblock %}

{% block content %}
	<div class="container">
	  {% set builtwith = [
		[
			'Digital Ocean', 
			'https://www.digitalocean.com', 
			'/img/credits/digitalocean.png'
		],
		[
			'Ubuntu', 
			'http://www.ubuntu.com', 
			'/img/credits/ubuntu.png'
		],
		[
			'PHP', 
			'https://php.net', 
			'/img/credits/php.png'
		],
		[
			'Phalcon', 
			'http://phalconphp.com/', 
			'/img/credits/phalcon.png'
		],
		[
			'Nginx', 
			'http://nginx.org', 
			'/img/credits/nginx.png'
		],
		[
			'Mysql', 
			'http://www.mysql.com', 
			'/img/credits/mysql.png'
		],
		[
			'Netbeans IDE', 
			'https://netbeans.org', 
			'/img/credits/netbeans.gif'
		]
	]
	  %}
	  {% set plugins = [
		['jQuery', 'Javascript', 'http://jquery.com'],
		['jQuery UI', 'Javascript', 'http://jqueryui.com'],
		['Dropzone', 'Javascript', 'http://www.dropzonejs.com'],
		['Tag it', 'Javascript', 'https://github.com/aehlke/tag-it'],
		['DataTables', 'Javascript', 'http://www.datatables.net'],
		['Cookiebanner', 'Javascript', 'https://github.com/dobarkod/cookie-banner'],
		['Summernote', 'Javascript', 'https://github.com/HackerWins/summernote'],
		['Simple Image', 'PHP', 'https://github.com/claviska/SimpleImage'],
		['Phalcon Mandrill', 'PHP', 'https://gitlab.com/tartan/phalconphp-mandrill-component'],
		['Materialize', 'CSS', 'http://materializecss.com'],
		['Font Awesome', 'CSS', 'http://fortawesome.github.io/Font-Awesome/']
	]	
	  %}
	  <div id="flash-container">
		{{ flashSession.output() }}
	  </div>
	  <h3>Built with</h3>
	  <hr />
	  <div class="row">
		{% for credit in builtwith %}
			<div class="col l3 m4 s6" style="padding-bottom: 10px; height: 200px;">
			  <h5><a href="{{ credit[1] }}">{{ credit[0] }}</a></h5>
			  <a href="{{ credit[1] }}">
				<img style="max-height: 100px;" src="{{ credit[2] }}" alt="{{ credit[0] }}" />
			  </a>
			</div>
		{% endfor %}
	  </div>
	  <div class="row">
		<div class="col s12">
		  <table class="table bordered striped condensed">
			<thead>
			  <tr>
				<td>Name</td>
				<td>Type</td>
				<td>Link</td>
			  </tr>
			</thead>
			<tbody>
			  {% for credit in plugins %}
				  <tr>
					<td>{{ credit[0] }}</td>
					<td>{{ credit[1] }}</td>
					<td><a href="{{ credit[2] }}">{{ credit[0] }}</a></td>
				  </tr>
			  {% endfor %}
			</tbody>
		  </table>
		</div>
	  </div>
	</div>
{% endblock %}

{% block javascripts %}
	<script type="text/javascript">
		$(function () {

		});
	</script>
{% endblock %}

{% block footer %}
	{% include "layouts/footer.volt" %}
{% endblock %}