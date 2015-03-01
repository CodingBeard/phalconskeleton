{# 
phalconskeleton
author Tim Marshall
copyright (c) 2015, Tim Marshall
#}
<footer class="page-footer">
  <div class="footer-copyright">
	<div class="container hide-on-small-only">
	  © 2015 <a href="http://codingbeard.com">CodingBeard.com</a>
	  <ul class="list-inline right">
		{% set _footernav = navbarObject.findFirst('name = "Footer"') %}
		{% if _footernav.id %}
			<li>
			  <a>|</a>
			</li>
			{% for navlink in _footernav.getNavlinks('level = 0') %}
				{% if navlink.children is iterable %}
					{% for child in navlink.children %}
						<li>
						  <a href="{{ url(child.link) }}">{{ child.label }}</a>
						</li>
						<li>
						  <a>|</a>
						</li>
					{% endfor %}
				{% else %}
					<li>
					  <a href="{{ url(navlink.link) }}">{{ navlink.label }}</a>
					</li>
					<li>
					  <a>|</a>
					</li>
				{% endif %}
			{% endfor %}
		{% endif %}
	  </ul>
	</div>
	<div class="hide-on-med-and-up center">
	  © 2015 <a href="http://codingbeard.com">CodingBeard.com</a>
	</div>
  </div>
</footer>