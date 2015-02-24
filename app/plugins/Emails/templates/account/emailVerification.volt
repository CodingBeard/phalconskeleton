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
	<meta itemprop="name" content="Confirm Email"/>
	<table width="100%" cellpadding="0" cellspacing="0">
	  <tr>
		<td class="content-block">
		  <h2>Hey {{ user.firstName|e }}!</h2>
		</td>
	  </tr>
	  <tr>
		<td class="content-block">
		  Thanks for signing up with us.
		</td>
	  </tr>
	  <tr>
		<td class="content-block">
		  We may need to send you notifications or password resets and it is important that we have a valid email address.
		</td>
	  </tr>
	  <tr>
		<td class="content-block aligncenter" itemprop="handler" itemscope itemtype="http://schema.org/HttpActionHandler">
		  <a href="http://{{ config.mail.domain }}/account/verify-email/{{ token }}" class="btn-primary" itemprop="url">Confirm email address</a>
		</td>
	  </tr>
	  <tr>
		<td class="content-block">
		  If you did not register an account with us, please ignore this email.
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