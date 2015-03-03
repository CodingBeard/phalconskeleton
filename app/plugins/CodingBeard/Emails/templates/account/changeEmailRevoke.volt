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
		  At {{ date(config.defaults.datetimeFormat) }} Your email address was changed to: {{ user.email|e }}.
		</td>
	  </tr>
	  <tr>
		<td class="content-block">
		  If you did not request this please revoke the change using the link below.
		</td>
	  </tr>
	  <tr>
		<td class="content-block aligncenter" itemprop="handler" itemscope itemtype="http://schema.org/HttpActionHandler">
		  <a href="http://{{ config.application.domain }}/account/revoke-email-change/{{ token }}" class="btn red" itemprop="url">Revoke change</a>
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