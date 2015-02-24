{# 
phalconskeleton
author Tim Marshall
copyright (c) 2015, Tim Marshall
#}
<div class="supernav">
  <div class="container">
	<div class="right">
	  {% if auth.id %}
		  <a href="{{ url('#') }}">Logout</a>
	  {% else %}
		  <a href="{{ url('account/login') }}">Login/Register</a>
	  {% endif %}
	</div>
  </div>
</div>