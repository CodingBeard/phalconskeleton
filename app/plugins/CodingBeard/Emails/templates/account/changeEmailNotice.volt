{# 
phalconskeleton
author Tim Marshall
copyright (c) 2015, Tim Marshall
#}
{% extends 'layouts/master.volt' %}

{% block header %}
	{% include "layouts/header.volt" %}
{% endblock %}

{% block content %}
	<meta itemprop="name" content="Reset Password"/>
	<table width="100%" cellpadding="0" cellspacing="0">
	  <tr>
		<td class="content-block">
		  <h2>Hey {{ user.firstName|e }}!</h2>
		</td>
	  </tr>
	  <tr>
		<td class="content-block">
		  At {{ date(config.defaults.datetimeFormat) }} Your email address was changed from {{ oldEmail|e }} to this one.
		</td>
	  </tr>
	  <tr>
		<td class="content-block regards">
		  Regards, <br />
		  {{ config.application.name }}
		</td>
	  </tr>
	</table>
{% endblock %}

{% block footer %}
	{% include "layouts/footer.volt" %}
{% endblock %}

{% block text %}
	
{% endblock %}