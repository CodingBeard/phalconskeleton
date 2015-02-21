{# 
phalconskeleton
author Tim Marshall
copyright (c) 2015, Tim Marshall
#}
{%
SET navigation = [
['Nav', [
	['Link 1', '#'],
	['Link 2', '#']
]]
]
%}
<nav role="navigation">
  <div class="container">
	<a id="logo-container" href="/" class="brand-logo">Phalcon</a>
	<ul id="nav-mobile" class="side-nav hide-on-large-only">
	  {% for one in navigation %}
		  {% if one[1] is iterable %}
			  <li class="no-padding">
				<ul class="collapsible">
				  <li>
					<a class="collapsible-header bold" data-activates="collapse1">{{ one[0] }}</a>
					<div id="collapse1" class="collapsible-body">
					  <ul>
						{% for two in one[1] %}
							<li>
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
				<a class="bold" href="{{ url(one[1]) }}">{{ one[0] }}</a>
			  </li>
		  {% endif %}
	  {% endfor %}
	  <li class="no-padding">
		<ul class="collapsible">
		  <li>
			<a class="collapsible-header bold" data-activates="collapse1">Site</a>
			<div id="collapse1" class="collapsible-body">
			  <ul>
				<li><a href="{{ url('site/terms') }}">Terms</a></li>
				<li><a href="{{ url('site/privacy') }}">Privacy</a></li>
				<li><a href="{{ url('site/credits') }}">Credits</a></li>
			  </ul>
			</div>
		  </li>
		</ul>
	  </li>
	</ul>

	<ul class="right hide-on-med-and-down">
	  {% for one in navigation %}
		  {% if one[1] is iterable %}
			  <li class="no-padding">
				<a class="dropdown-button" href="#" data-activates="dropdown">{{ one[0] }}</a>
				<ul id="dropdown" class="dropdown-content">
				  {% for two in one[1] %}
					  <li>
						<a href="{{ url(two[1]) }}">{{ two[0] }}</a>
					  </li>
				  {% endfor %}
				</ul>
			  </li>
		  {% else %}
			  <li>
				<a class="bold" href="{{ url(one[1]) }}">{{ one[0] }}</a>
			  </li>
		  {% endif %}
	  {% endfor %}
	</ul>
	<a href="#" data-activates="nav-mobile" class="button-collapse white-text"><i class="mdi-navigation-menu"></i></a>
  </div>
</nav>