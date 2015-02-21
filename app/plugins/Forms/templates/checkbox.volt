{# 
QUKsite
author Tim Marshall
copyright (c) 2014, Tim Marshall
#}
{% if size is not defined %}
	{% set size = 12 %}
{% endif %}
<div class="col l{{ size }} m12 s12">
  <div class="section">
	<div>
	  <input {{ required }} type="checkbox" id="{{ key|escape_attr }}" name="{{ key|escape_attr }}" class="{{ class|escape_attr }}" {{ defaultValue }}> 
	  <label for="{{ key|escape_attr }}">
		{{ label }}
		<span class="light">
		  {{ sublabel }}
		</span>
	  </label>
	</div>
  </div>
</div>