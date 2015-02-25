{# 
phalconskeleton
author Tim Marshall
copyright (c) 2015, Tim Marshall
#}
{% if size is not defined %}
	{% set size = 12 %}
{% endif %}
<div class="col l{{ size }} m12 s12">
  {% if errorMessage %}
	  <div class="alert alert-danger alert-dismissible">
		{{ errorMessage }}
	  </div>
  {% endif %}
  <label class="{{ error|escape_attr }}">
	{{ label }} 
	{% if required is true %}
		<strong style="color: red;">*</strong>
	{% endif %}
	<span class="sublabel">
	  {{ sublabel }}
	</span>
  </label>
  {% if options is iterable %}
	  {% for option in options %}
		  <div>
			<input id="{{ option.key|escape_attr }}" name="{{ option.key|escape_attr }}" class="{{ class|escape_attr }}" type="checkbox" {{ option.default }}> 
			<label for="{{ option.key|escape_attr }}">{{ option.label }}</label>
		  </div>
	  {% endfor %}
  {% endif %}
</div>