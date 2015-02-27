{# 
phalconskeleton
author Tim Marshall
copyright (c) 2015, Tim Marshall
#}
{%
SET navigation = [
['Users', [
	['List', 'admin/users'],
	['Roles', 'admin/users/roles']
]]
]
%}
<nav role="navigation">
  <div class="container">
	<a href="{{ url('admin') }}" class="brand-logo hide-on-large-only">Phalcon</a>
	<ul id="nav-mobile" class="side-nav fixed">
	  <li class="logo hide-on-med-and-down">
		<a href="{{ url('admin') }}" class="brand-logo">Phalcon</a>
	  </li>
	  {% for one in navigation %}
		  {% set active = '' %}
		  {% if one[1] is iterable %}
			  {% for two in one[1] %}
				  {% if _SERVER['REQUEST_URI'] == url(two[1]) %}
					  {% set active = 'active' %}
				  {% endif %}
			  {% endfor %}
			  <li class="no-padding">
				<ul class="collapsible">
				  <li class="bold {{ active }}">
					<a class="collapsible-header {{ active }}" data-activates="collapse">{{ one[0] }}</a>
					<div id="collapse" class="collapsible-body">
					  <ul>
						{% for two in one[1] %}
							{% set active = '' %}
							{% if _SERVER['REQUEST_URI'] == url(two[1]) %}
								{% set active = 'active' %}
							{% endif %}
							<li class="{{ active }}">
							  <a href="{{ url(two[1]) }}">{{ two[0] }}</a>
							</li>
						{% endfor %}
					  </ul>
					</div>
				  </li>
				</ul>
			  </li>
		  {% else %}
			  <li>
				<a href="{{ url(one[1]) }}">{{ one[0] }}</a>
			  </li>
		  {% endif %}
	  {% endfor %}
	  <li class="no-padding hide-on-med-and-up">
		<ul class="collapsible">
		  <li class="bold">
			<a class="collapsible-header" data-activates="collapse">Site</a>
			<div id="collapse" class="collapsible-body">
			  <ul>
				<li><a href="{{ url('terms') }}">Terms</a></li>
				<li><a href="{{ url('privacy') }}">Privacy</a></li>
				<li><a href="{{ url('credits') }}">Credits</a></li>
			  </ul>
			</div>
		  </li>
		</ul>
	  </li>
	</ul>
	<a href="#" data-activates="nav-mobile" class="button-collapse white-text"><i class="mdi-navigation-menu"></i></a>
  </div>
</nav>