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
  <div class="switch">
	<label>
	  {{ offText }}
	  <input name="{{ key|escape_attr }}" class="{{ class|escape_attr }}" type="checkbox" {{ default }}>
	  <span class="lever"></span>
	  {{ onText }}
	</label>
  </div>
</div>
{% if toggleRequired is iterable %}
	<script type="text/javascript">
		$('[name="{{ key|escape_js }}"]').change(function () {
		{% for inputKey in toggleRequired %}
			if ($('[name="{{ inputKey|escape_js }}"]').is('[required]')) {
				$('[name="{{ inputKey|escape_js }}"]').removeAttr('required').removeAttr('placeholder');
				$('[for="{{ inputKey|escape_js }}"]').find('strong').remove();
			}
			else {
				$('[name="{{ inputKey|escape_js }}"]').attr('required', true);
				$('[for="{{ inputKey|escape_js }}"]').append('<strong style="color: red;">*</strong>');
			}
		{% endfor %}
		});
	</script>
{% endif %}