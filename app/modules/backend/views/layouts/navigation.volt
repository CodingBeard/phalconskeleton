{# 
@package: phalconskeleton
@author: Tim Marshall <Tim@CodingBeard.com>
@copyright: (c) 2015, Tim Marshall
@license: New BSD License
#}
<nav role="navigation">
  <ul class="hide-on-med-and-down">
	{% set _backendnav = navbarObject.findFirst('name = "Backend"') %}
	{# Header sub nav (level 3) #}
	{% for navlink in _backendnav.getNavlinks('level = 1') %}
		{% if navlink.inUrl(_SERVER['REQUEST_URI']) and navlink.children is iterable %}
			<li>
			  <a href="{{ url(navlink.link) }}">{{ navlink.label }}</a>
			</li>
			{% for child in navlink.children %}
				<li>
				  <a href="{{ url(child.link) }}">{{ child.label }}</a>
				</li>
			{% endfor %}
			{% break %}
		{% endif %}
	{% endfor %}
  </ul>
  {% cache "backend_navbar" 3600 %}
  <div class="container">
	{# Replace (level 3) nav with logo/name on mobile #}
	<div class="hide-on-large-only">
	  <a href="{{ url('admin') }}" class="brand-logo h-logo">
		<img class="h-logo" src="{{ url('img/icons/logo-grey-70.png') }}" alt="CodingBeard" />
		Admin
	  </a>
	</div>
	<ul id="nav-mobile" class="side-nav fixed">
	  {# Hide inline logo/name on mobile #}
	  <li class="logo hide-on-med-and-down">
		<img src="{{ url('img/icons/logo-70.png') }}" alt="CodingBeard" />
		<a href="{{ url('admin') }}" class="brand-logo">
		  Admin
		</a>
	  </li>
	  {# Main nav #}
	  <div class="hide-on-med-and-down">
		{% if _backendnav.id %}
			{% for navlink in _backendnav.getNavlinks('level = 0') %}
				{% if navlink.children is iterable %}
					{% for child in navlink.children %}
					{% endfor %}
					<li class="no-padding">
					  <ul class="collapsible">
						<li class="bold">
						  <a class="collapsible-header" data-activates="collapse1">{{ navlink.label }}</a>
						  <div id="collapse1" class="collapsible-body">
							<ul>
							  {% for child in navlink.children %}
								  <li>
									<a href="{{ url(child.link) }}">{{ child.label }}</a>
								  </li>
							  {% endfor %}
							</ul>
						  </div>
						</li>
					  </ul>
					</li>
				{% else %}
					<li class="bold">
					  <a href="{{ url(navlink.link) }}">{{ navlink.label }}</a>
					</li>
				{% endif %}
			{% endfor %}
		</div>
		{# Mobile nav #}
		<div class="hide-on-med-and-up">
		  {% for navlink in _backendnav.getNavlinks('level = 0') %}
			  {% set active = '' %}
			  {% if navlink.children is iterable %}
				  {% for child in navlink.children %}
					  {% if child.children is iterable %}
						  <li class="no-padding">
							<ul class="collapsible">
							  <li class="bold">
								<a class="collapsible-header" data-activates="collapse2">{{ child.label }}</a>
								<div id="collapse2" class="collapsible-body">
								  <ul>
									<li>
									  <a href="{{ url(child.link) }}">Index</a>
									</li>
									{% for subchild in child.children %}
										<li class="{{ active }}">
										  <a class="{{ active }}" href="{{ url(subchild.link) }}">{{ subchild.label }}</a>
										</li>
									{% endfor %}
								  </ul>
								</div>
							  </li>
							</ul>
						  </li>
					  {% else %}
						  <li>
							<a href="{{ url(child.link) }}">{{ child.label }}</a>
						  </li>
					  {% endif %}
				  {% endfor %}
			  {% else %}
				  <li class="bold">
					<a href="{{ url(navlink.link) }}">{{ navlink.label }}</a>
				  </li>
			  {% endif %}
		  {% endfor %}
		  {# footer nav appended to mobile nav #}
		  {% set _footernav = navbarObject.findFirst('name = "Footer"') %}
		  {% if _footernav.id %}
			  {% for navlink in _footernav.getNavlinks('level = 0') %}
				  {% if navlink.children is iterable %}
					  <li class="no-padding">
						<ul class="collapsible">
						  <li class="bold">
							<a class="collapsible-header" data-activates="collapse1">{{ navlink.label }}</a>
							<div id="collapse1" class="collapsible-body">
							  <ul>
								{% for child in navlink.children %}
									<li>
									  <a href="{{ url(child.link) }}">{{ child.label }}</a>
									</li>
								{% endfor %}
							  </ul>
							</div>
						  </li>
						</ul>
					  </li>
				  {% else %}
					  <li class="bold">
						<a href="{{ url(navlink.link) }}">{{ navlink.label }}</a>
					  </li>
				  {% endif %}
			  {% endfor %}
		  {% endif %}

		</div>
	  {% endif %}
	</ul>
	<a href="#" data-activates="nav-mobile" class="button-collapse white-text"><i class="mdi-navigation-menu"></i></a>
  </div>
</nav>
{% endcache %}