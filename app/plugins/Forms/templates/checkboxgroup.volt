{# 
QUKsite
author Tim Marshall
copyright (c) 2014, Tim Marshall
#}
{% if size is not defined %}
	{% set size = 12 %}
{% endif %}
<div class="col l{{ size }} m12 s12">
  <label class="{{ error|escape_attr }}">
	{{ label }}
	<span class="light">
	  {{ sublabel }}
	</span>
  </label>
  <div class="section">
	{% if options is iterable %}
		{% for option in options %}
			<div>
			  <input {{ required }} id="{{ option.key|escape_attr }}" name="{{ option.key|escape_attr }}" class="{{ class|escape_attr }}" type="checkbox" {{ option.default }}> 
			  <label for="{{ option.key|escape_attr }}">{{ option.label }}</label>
			</div>
		{% endfor %}
	{% endif %}
  </div>
</div>