{# 
phalconskeleton
author Tim Marshall
copyright (c) 2015, Tim Marshall
#}
<form autocomplete="off" method="POST" action="" enctype="application/x-www-form-urlencoded">
  {{ auth.getSecurityField() }}
  <div class="form-header">
	<h3>{{ title }}</h3>
	<p>{{ subtitle }}</p>
  </div>
  <p>
	{{ description }}
  </p>
  <hr />
  <div class="row">
	<div class="col offset-l{{ outerRatio }} l{{ innerRatio }} m12 s12">
	  <div id="flash-container">
		{{ notifications }}
	  </div>
	  {% set gridcount = 0 %}
	  {% if fields is iterable %}
		  {% for field in fields %}
			  {% if gridcount == 0 %}
				  <div class="row">
				  {% endif %}
				  {{ form.renderField(field) }}
				  {% set gridcount = gridcount + field.size %}
				  {% if gridcount >= 12 %}
				  </div>
				  {% set gridcount = 0 %}
			  {% endif %}
		  {% endfor %}
	  {% endif %}
	  <div class="row">
		<button id="submit" class="btn green" type="submit">{{ submitButton }}</button>
		<a class="btn white black-text right" href="{{ cancelHref }}">{{ cancelButton }}</a>
	  </div>
	</div>
  </div>
</form>