{# 
QUKsite
author Tim Marshall
copyright (c) 2014, Tim Marshall
#}
{% if size is not defined %}
	{% set size = 12 %}
{% endif %}
<div class="col l{{ size }} m12 s12">
  <label class="{{ error|escape_attr }}" for="{{ key }}">
	{{ label }}
	<span class="light">
	  {{ sublabel }}
	</span>
  </label>
  <div class="section">
	{% if options is iterable %}
		{% for count, option in options %}
			<div>
			  <input {{ required }} id="{{ key ~ count|escape_attr }}" name="{{ key|escape_attr }}" class="{{ class|escape_attr }}" type="radio" value="{{ option.value|escape_attr }}" {{ option.default }}> 
			  <label for="{{ key ~ count|escape_attr }}">{{ option.label }}</label>
			</div>
		{% endfor %}
	{% endif %}
  </div>
</div>