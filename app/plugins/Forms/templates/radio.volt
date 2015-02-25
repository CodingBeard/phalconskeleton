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
  <label class="{{ error|escape_attr }}" for="{{ key }}">
	{{ label }} 
	{% if required is true %}
		<strong style="color: red;">*</strong>
	{% endif %}
	<span class="sublabel">
	  {{ sublabel }}
	</span>
  </label>
  {% if options is iterable %}
	  {% for count, option in options %}
		  <div>
			<input {{ requiredAttribute }} id="{{ key ~ count|escape_attr }}" name="{{ key|escape_attr }}" class="{{ class|escape_attr }}" type="radio" value="{{ option.value|escape_attr }}" {{ option.default }}> 
			<label for="{{ key ~ count|escape_attr }}">{{ option.label }}</label>
		  </div>
	  {% endfor %}
  {% endif %}
</div>