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
	<label class="{{ error|escape_attr }}" for="{{ key }}">
	  {{ label }}
	  <span class="light">
		{{ sublabel }}
	  </span>
	</label>
	<textarea {{ required }} name="{{ key|escape_attr }}" class="materialize-textarea {{ error|escape_attr }} {{ class|escape_attr }}">{{ defaultValue }}</textarea>
  </div>
</div>