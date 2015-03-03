{# 
@package: phalconskeleton
@author: Tim Marshall <Tim@CodingBeard.com>
@copyright: (c) 2015, Tim Marshall
@license: New BSD License
#}
{% extends 'layouts/master.volt' %}

{% block head %}

{% endblock %}

{% block header %}
	{% include "layouts/header.volt" %}
	{% include "layouts/navigation.volt" %}
{% endblock %}

{% block content %}
	<div class="container">
	  <div id="flash-container">
		{{ flashSession.output() }}
	  </div>
	  {%- macro getPanel(page, content) %}
	  {% if content.children.count() %}
		  <div class="col s{{ content.width }} offset-s{{ content.offset }}">
			{% for child in content.getChildren(['order': 'ordering']) %}
				{{ getPanel(page, child) }}
			{% endfor %}
		  </div>
	  {% else %}
		  <div class="col s{{ content.width }} offset-s{{ content.offset }}">
			<p>{{ content.getContent() }}</p>
		  </div>
	  {% endif %}
	  {%- endmacro %}
	  {% if page.contents is iterable %}
		  {% for content in page.getContents(['parent_id IS NULL', 'order': 'ordering']) %}
			  {{ page.newRow(content) }}
			  {{ getPanel(page, content) }}
			  {{ page.endRow(content) }}
		  {% endfor %}
	  {% endif %}
	</div>
{% endblock %}

{% block javascripts %}
	<script type="text/javascript">
		$(function () {

		});
	</script>
{% endblock %}

{% block footer %}
	{% include "layouts/footer.volt" %}
{% endblock %}