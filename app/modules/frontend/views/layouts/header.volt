{# 
@package: phalconskeleton
@author: Tim Marshall <Tim@CodingBeard.com>
@copyright: (c) 2015, Tim Marshall
@license: New BSD License
#}
<div class="supernav">
  <div class="container">
	<div class="right">
	  {% if auth.loggedIn %}
		  {% if auth.isAdmin %}
			  {% set _headernav = navbarObject.findFirst('name = "Frontend_header_admin"') %}
		  {% else %}
			  {% set _headernav = navbarObject.findFirst('name = "Frontend_header_member"') %}
		  {% endif %}
	  {% else %}
		  {% set _headernav = navbarObject.findFirst('name = "Frontend_header_guest"') %}
	  {% endif %}
	  {% if _headernav.navlinks is iterable %}/
		  {% for navlink in _headernav.navlinks %}
			  <a href="{{ url(navlink.link) }}">{{ navlink.label }}</a> /
		  {% endfor %}
	  {% endif %}
	</div>
  </div>
</div>