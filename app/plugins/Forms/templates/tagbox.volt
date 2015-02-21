{# 
QUKsite
author Tim Marshall
copyright (c) 2014, Tim Marshall
#}
<div class="col l{{ size }} m12 s12">
  <label class="{{ error|escape_attr }}" for="{{ key }}">
	{{ label }}
	<span class="light">
	  {{ sublabel }}
	</span>
  </label>
  <div class="section">
	<ul id="{{ key|escape_attr }}">
	  {% if options is iterable %}
		  {% for option in options %}
			  {% if option.default %}
				  <li>
					{{ option.label|e }}
				  </li>
			  {% endif %}
		  {% endfor %}
	  {% endif %}
	</ul>
  </div>
</div>
<script type="text/javascript">
	$(function () {
		var tagValues = {{ json_encode(tagValues) }};
		var tagLabels = {{ json_encode(tagLabels) }};
		$('#{{ key|escape_attr }}').tagit({
			availableTags: tagLabels,
			autocomplete: {delay: 0, minLength: 2},
			removeConfirmation: true,
			fieldName: '{{ key|escape_attr }}',
			tagLimit: {{ tagLimit }},
			beforeTagAdded: function (event, ui) {
				if ($.inArray(ui.tagLabel, tagLabels) === -1) {
					return false;
				}
			},
			afterTagAdded: function (event, ui) {
				$(this).append('<input type="hidden" value="' + tagValues[ui.tagLabel] + '" name="{{ key|escape_attr }}[]" class="{{ key|escape_attr }}">');
			},
			afterTagRemoved: function (event, ui) {
				$('.{{ key|escape_attr }}[value="' + tagValues[ui.tagLabel] + '"]').remove();
			}
		});
		{% if required is true %}
			$('form').submit(function (e) {
				if (!$("[name='{{ key|escape_attr }}[]']").length) {
					e.preventDefault();
					$('#{{ key|escape_attr }}').css('border', '1px solid red');
				}
			});
		{% endif %}
	});
</script>