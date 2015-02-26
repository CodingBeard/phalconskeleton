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
	<div class="container">
	  <div id="flash-container">
		{{ flashSession.output() }}
	  </div>
	  <h3>My Account</h3>
	  <hr />
	  <table class="table bordered striped condensed">
		<tbody>
		  <tr>
			<td>Name</td>
			<td>{{ auth.getUser().getName() }}</td>
		  </tr>
		  <tr>
			<td>Date of Birth</td>
			<td>{{ date(config.defaults.dateFormat, auth.getUser().DoB|strtotime) }}</td>
		  </tr>
		  <tr>
			<td>Email</td>
			<td>{{ auth.getUser().email|e }}</td>
		  </tr>
		</tbody>
	  </table>
	  <ul class="list-inline center">
		<li>
		  <a class="btn-flat blue-hover btn-small" href="{{ url('account/change-pass') }}">Update Password</a>
		</li>
		<li>
		  <a class="btn-flat blue-hover btn-small" href="{{ url('account/change-info') }}">Update Details</a>
		</li>
		<li>
		  <a class="btn-flat blue-hover btn-small" href="{{ url('account/change-email') }}">Update Email</a>
		</li>
	  </ul>
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