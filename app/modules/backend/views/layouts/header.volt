{# 
@package: phalconskeleton
@author: Tim Marshall <Tim@CodingBeard.com>
@copyright: (c) 2015, Tim Marshall
@license: New BSD License
#}
<div class="supernav">
  <div class="container">
    <div class="right">
      {% set _headernav = navbarObject.findFirst('name = "Backend_header"') %}
      {% if _headernav.navlinks is iterable %}/
        {% for navlink in _headernav.navlinks %}
          <a href="{{ url(navlink.link) }}">{{ navlink.label }}</a> /
        {% endfor %}
      {% endif %}
    </div>
  </div>
</div>