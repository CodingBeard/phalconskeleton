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
	<div class="switch">
	  <label>
		{{ offText }}
		<input name="{{ key|escape_attr }}" class="{{ class|escape_attr }}" type="checkbox" {{ defaultValue }}>
		<span class="lever"></span>
		{{ onText }}
	  </label>
	</div>
  </div>
</div>