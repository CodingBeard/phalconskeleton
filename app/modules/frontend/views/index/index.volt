{# 
phalconskeleton
author Tim Marshall
copyright (c) 2015, Tim Marshall
#}
{% extends 'layouts/master.volt' %}

{% block head %}

{% endblock %}

{% block header %}
	{% include "layouts/header.volt" %}
	{% include "layouts/navigation.volt" %}
{% endblock %}

{% block content %}
	<div style="height: 100%;border: black;" class="container">
	  <div id="flash-container">
		{{ flashSession.output() }}
	  </div>
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