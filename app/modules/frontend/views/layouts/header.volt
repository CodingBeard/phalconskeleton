{# 
phalconskeleton
author Tim Marshall
copyright (c) 2015, Tim Marshall
#}
<div class="supernav">
  <div class="container">
	<div class="right">
	  {% if auth.loggedIn %}
		  {% if auth.isAdmin %}
			  <a href="{{ url('admin') }}">Admin</a> /
		  {% endif %}
		  <a href="{{ url('account') }}">Account</a> /
		  <a href="{{ url('logout') }}">Logout</a>
	  {% else %}
		  <a href="{{ url('login') }}">Login</a> /
		  <a href="{{ url('register') }}">Register</a>
	  {% endif %}
	</div>
  </div>
</div>