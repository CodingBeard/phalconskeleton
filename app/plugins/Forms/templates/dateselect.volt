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
  <div class="inline-selects">
	<select {{ requiredAttribute }} name="{{ key|escape_attr }}-year" class="browser-default {{ error|escape_attr }} {{ class|escape_attr }}">
	  <option disabled value="">Year</option>
	  {% if ranges.year is iterable %}
		  {% for range in ranges.year %}
			  <option value="{{ range.value|escape_attr }}" {{ range.default }}>{{ range.value }}</option>
		  {% endfor %}
	  {% endif %}
	</select>
	<select {{ requiredAttribute }} name="{{ key|escape_attr }}-month" class="browser-default {{ error|escape_attr }} {{ class|escape_attr }}">
	  <option disabled value="">Month</option>
	  {% if ranges.month is iterable %}
		  {% for range in ranges.month %}
			  <option value="{{ range.value|escape_attr }}" {{ range.default }}>{{ range.value }}</option>
		  {% endfor %}
	  {% endif %}
	</select>
	<select {{ requiredAttribute }} name="{{ key|escape_attr }}-day" class="browser-default {{ error|escape_attr }} {{ class|escape_attr }}">
	  <option disabled value="">Day</option>
	  {% if ranges.day is iterable %}
		  {% for range in ranges.day %}
			  <option value="{{ range.value|escape_attr }}" {{ range.default }}>{{ range.value }}</option>
		  {% endfor %}
	  {% endif %}
	</select>
  </div>
</div>