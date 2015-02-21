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
	<select {{ required }} name="{{ key|escape_attr }}" class="{{ error|escape_attr }} {{ class|escape_attr }}">
	  <option disabled value="">Choose</option>
	  {% if options is iterable %}
		  {% for option in options %}
			  <option value="{{ option.value|escape_attr }}" {{ option.default }}>{{ option.label }}</option>
		  {% endfor %}
	  {% endif %}
	</select>
  </div>
</div>