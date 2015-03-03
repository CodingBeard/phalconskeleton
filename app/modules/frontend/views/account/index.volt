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
	  <h3>My Account</h3>
	  <hr />
	  <table class="table bordered striped condensed">
		<tbody>
		  <tr>
			<td>Last login</td>
			{% set login = auth.getUser().logins.getLast() %}
			<td>{{ date(config.defaults.datetimeFormat, login.attempt) }},  from {{ login.ip|e }}</td>
		  </tr>
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
	  {% if auth.getUser().emailchanges is iterable %}
		  <h5>Previous emails</h5>
		  <table class="datatable condensed bordered striped responsive-table invisible">
			<thead>
			  <tr>
				<td>Date of change</td>
				<td>Email</td>
			  </tr>
			</thead>
			<tbody>
			  {% for change in auth.getUser().getEmailchanges(['order': 'date DESC']) %}
				  <tr>
					<td>{{ date(config.defaults.datetimeFormat, change.date|strtotime) }}</td>
					<td>{{ change.oldEmail|e }}</td>
				  </tr>
			  {% endfor %}
			</tbody>
		  </table>
	  {% endif %}
	</div>
{% endblock %}

{% block javascripts %}
	<script type="text/javascript">
		$(function () {
			$('.datatable').dataTable({sort: false});
		});
	</script>
{% endblock %}

{% block footer %}
	{% include "layouts/footer.volt" %}
{% endblock %}