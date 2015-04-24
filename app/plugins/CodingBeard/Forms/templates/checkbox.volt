{# 
phalconskeleton
author Tim Marshall
copyright (c) 2015, Tim Marshall
#}
{% if size is not defined %}
  {% set size = 12 %}
{% endif %}
{% if indent is not defined %}
  {% set indent = 0 %}
{% endif %}
<div class="col offset-l{{ indent }} l{{ size }} m12 s12">
  {% if errorMessage %}
    <div class="alert alert-danger alert-dismissible">
      {{ errorMessage }}
    </div>
  {% endif %}
  <input {{ requiredAttribute }} type="checkbox" id="{{ key|escape_attr }}" name="{{ key|escape_attr }}"
                                 class="{{ class|escape_attr }}" {{ default }}>
  <label for="{{ key|escape_attr }}">
    {{ label }}
    {% if required is true %}
      <strong style="color: red;">*</strong>
    {% endif %}
    <span class="sublabel">
	  {{ sublabel }}
	</span>
  </label>
</div>