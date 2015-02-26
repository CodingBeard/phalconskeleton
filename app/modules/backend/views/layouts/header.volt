{# 
phalconskeleton
author Tim Marshall
copyright (c) 2015, Tim Marshall
#}
<div class="supernav">
  <div class="container">
	<div class="right">
	  {% if auth.loggedIn %}
		  <a href="{{ url('') }}">Back</a> /
		  <a href="{{ url('logout') }}">Logout</a>
	  {% else %}
		  <a href="{{ url('login') }}">Login/Register</a>
	  {% endif %}
	</div>
  </div>
</div>