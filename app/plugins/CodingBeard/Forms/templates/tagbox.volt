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
  <label class="{{ error|escape_attr }}" for="{{ key }}">
    {{ label }}
    {% if required is true %}
      <strong style="color: red;">*</strong>
    {% endif %}
    <span class="sublabel">
	  {{ sublabel }}
	</span>
  </label> <br>

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
      autocomplete: {delay: 0, minLength: {{ minLength }}},
      showAutocompleteOnFocus: {{ autocompleteOnFocus }},
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