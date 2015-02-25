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
		  You have requested to reset your password, please click the link below to do so.
		</td>
	  </tr>
	  <tr>
		<td class="content-block aligncenter" itemprop="handler" itemscope itemtype="http://schema.org/HttpActionHandler">
		  <a href="http://{{ config.application.domain }}/account/reset-pass/{{ token }}" class="btn-primary" itemprop="url">Reset Password</a>
		</td>
	  </tr>
	  <tr>
		<td class="content-block">
		  If you did not make this request please ignore it.
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